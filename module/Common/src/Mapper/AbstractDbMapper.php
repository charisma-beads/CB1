<?php

namespace Common\Mapper;

use Common\Model\ModelAwareTrait;
use Common\Model\ModelInterface;
use Laminas\Db\Adapter\AdapterAwareTrait;
use Laminas\Db\ResultSet\AbstractResultSet;
use Laminas\Db\ResultSet\HydratingResultSet;
use Laminas\Db\Sql\Expression;
use Laminas\Db\Sql\Sql;
use Laminas\Db\Sql\Select;
use Laminas\Paginator\Paginator;
use Laminas\Paginator\Adapter\DbSelect;


class AbstractDbMapper implements
    MapperInterface,
    DbAdapterAwareInterface
{
    use AdapterAwareTrait,
        ModelAwareTrait;

    /**
     * Name of table
     *
     * @var string
     */
    protected $table;

    /**
     * name of primary column
     *
     * @var string
     */
    protected $primary;

    /**
     * @var Sql
     */
    protected $sql;

    /**
     * @var HydratingResultSet
     */
    protected $resultSetPrototype;

    /**
     * @var boolean
     */
    protected $usePaginator = false;

    /**
     * @var array
     */
    protected $paginatorOptions = [];

    /**
     * @var bool
     */
    protected $mysql57Compatible = false;

    /**
     * return an instance of Select
     *
     * @param string|null $tableName
     * @return Select
     */
    public function getSelect($tableName = null)
    {
        return $this->getSql()->select($tableName ?: $this->getTable());
    }

    /**
     * gets the resultSet
     *
     * @return HydratingResultSet
     */
    protected function getResultSet()
    {
        if (!$this->resultSetPrototype instanceof HydratingResultSet) {
            $resultSetPrototype = new HydratingResultSet;
            $resultSetPrototype->setHydrator($this->getHydrator());
            $resultSetPrototype->setObjectPrototype($this->getModel());
            $this->resultSetPrototype = $resultSetPrototype;
        }

        return clone $this->resultSetPrototype;
    }

    /**
     * Gets one or more rows by its id
     *
     * @param $id
     * @param null|string $col
     * @return array|ModelInterface
     */
    public function getById($id, $col = null)
    {
        $col = ($col) ?: $this->getPrimaryKey();
        $select = $this->getSelect()->where([$col => $id]);
        $resultSet = $this->fetchResult($select);

        if ($resultSet->count() > 1) {
            $rowSet = [];
            foreach ($resultSet as $row) {
                $rowSet[] = $row;
            }
        } elseif ($resultSet->count() === 1) {
            $rowSet = $resultSet->current();
        } else {
            $rowSet = $this->getModel();
        }

        return $rowSet;
    }

    /**
     * @param $id
     * @param Select|null $select
     * @return null|ModelInterface
     */
    public function getPrevious($id, Select $select = null)
    {

        $whereSelect = $select ?? $this->getSql()->select();
        $whereSelect->from($this->getTable())
            ->columns([
                $this->getPrimaryKey() => new Expression('MAX(' . $this->getPrimaryKey() . ')')
            ])->where->lessThan($this->getPrimaryKey() , $id);

        $select = $this->getSelect();
        $select->where([$this->getPrimaryKey() => $whereSelect]);

        $result = $this->fetchResult($select);
        $row = $result->current();

        return $row;
    }

    /**
     * @param $id
     * @param null|Select $select
     * @return null|ModelInterface
     */
    public function getNext($id, Select $select = null)
    {
        $whereSelect = $select ?? $this->getSql()->select();
        $whereSelect->from($this->getTable())
            ->columns([
                $this->getPrimaryKey() => new Expression('MIN(' . $this->getPrimaryKey() . ')')
            ])->where->greaterThan($this->getPrimaryKey() , $id);

        $select = $this->getSelect();
        $select->where([$this->getPrimaryKey() => $whereSelect]);

        $result = $this->fetchResult($select);
        $row = $result->current();

        return $row;
    }

    /**
     * Fetches all rows from database table.
     *
     * @param null|string $sort
     * @return HydratingResultSet|\Laminas\Db\ResultSet\ResultSet|Paginator
     */
    public function fetchAll($sort = null)
    {
        $select = $this->getSelect();
        $select = $this->setSortOrder($select, $sort);

        $resultSet = $this->fetchResult($select);

        return $resultSet;
    }

    /**
     * basic search on table data
     *
     * @param array $search
     * @param string $sort
     * @param Select $select
     * @return \Laminas\Db\ResultSet\ResultSet|Paginator|HydratingResultSet
     */
    public function search(array $search, $sort, $select = null)
    {
        $select = ($select) ?: $this->getSelect();

        foreach ($search as $key => $value) {
            if (!$value['searchString'] == '') {
                if (substr($value['searchString'], 0, 1) == '=' && $key == 0) {
                    $id = (int)substr($value['searchString'], 1);
                    $select->where->equalTo($this->getTable() . '.' .$this->getPrimaryKey(), $id);
                } else {
                    $where = $select->where->nest();
                    $c = 0;

                    foreach ($value['columns'] as $column) {
                        if ($c > 0) $where->or;
                        $where->like($column, '%' . $value['searchString'] . '%');
                        $c++;
                    }

                    $where->unnest();
                }
            }
        }

        $select = $this->setSortOrder($select, $sort);

        return $this->fetchResult($select);
    }

    /**
     * Inserts a new row into database returns insertId
     *
     * @param array $data
     * @param string $table
     * @return int|null
     */
    public function insert(array $data, $table = null)
    {
        $table = ($table) ?: $this->getTable();
        $sql = $this->getSql();
        $insert = $sql->insert($table);

        $insert->values($data);

        $statement = $sql->prepareStatementForSqlObject($insert);
        $result = $statement->execute();

        return $result->getGeneratedValue();
    }

    /**
     * Updates a database row/s, return affected rows
     *
     * @param array $data
     * @param $where
     * @param null $table
     * @return int
     */
    public function update(array $data, $where, $table = null)
    {
        $table = ($table) ?: $this->getTable();
        $sql = $this->getSql();
        $update = $sql->update($table);

        $update->set($data)
            ->where($where);

        $statement = $sql->prepareStatementForSqlObject($update);
        $result = $statement->execute();

        return $result->getAffectedRows();
    }

    /**
     * Deletes a row/s in the database returns number
     * of rows affected
     *
     * @param $where
     * @param null $table
     * @return int
     */
    public function delete($where, $table = null)
    {
        $table = ($table) ?: $this->getTable();
        $sql = $this->getSql();
        $delete = $sql->delete($table);

        $delete->where($where);

        $statement = $sql->prepareStatementForSqlObject($delete);
        $result = $statement->execute();

        return $result->getAffectedRows();
    }

    /**
     * @param $use
     * @return $this
     */
    public function setUsePaginator($use)
    {
        $this->usePaginator = $use;
        return $this;
    }

    /**
     * @return bool
     */
    public function usePaginator()
    {
        return $this->usePaginator;
    }

    /**
     * @return array
     */
    public function getPaginatorOptions()
    {
        return $this->paginatorOptions;
    }

    /**
     * @param array $paginatorOptions
     * @return $this
     */
    public function setPaginatorOptions(array $paginatorOptions)
    {
        $this->paginatorOptions = $paginatorOptions;
        return $this;
    }

    /**
     * Paginates the result set
     *
     * @param Select $select
     * @param AbstractResultSet $resultSet
     * @return Paginator
     */
    public function paginate($select, $resultSet = null)
    {
        $resultSet = $resultSet ?: $this->getResultSet();
        $adapter = new DbSelect($select, $this->getAdapter(), $resultSet);
        $paginator = new Paginator($adapter);

        $options = $this->getPaginatorOptions();

        if (isset($options['limit'])) {
            $paginator->setItemCountPerPage($options['limit']);
        }

        if (isset($options['page'])) {
            $paginator->setCurrentPageNumber($options['page']);
        }

        $paginator->setPageRange(5);

        return $paginator;
    }

    /**
     * Fetches the result of select from database
     *
     * @param Select $select
     * @param AbstractResultSet $resultSet
     * @return \Laminas\Db\ResultSet\ResultSet|Paginator|HydratingResultSet
     */
    protected function fetchResult(Select $select, AbstractResultSet $resultSet = null)
    {
        $resultSet = $resultSet ?: $this->getResultSet();
        $resultSet->buffer();

        if ($this->usePaginator()) {
            $this->setUsePaginator(false);
            $resultSet = $this->paginate($select, $resultSet);
        } else {
            $statement = $this->getSql()->prepareStatementForSqlObject($select);
            $result = $statement->execute();
            $resultSet->initialize($result);
        }

        return $resultSet;
    }

    /**
     * Sets database query limit
     *
     * @param Select $select
     * @param int $count
     * @param int $offset
     * @return Select
     */
    public function setLimit(Select $select, $count, $offset)
    {
        if ($count === null) {
            return $select;
        }

        return $select->limit($count)
            ->offset($offset);
    }

    /**
     * Sets sort order of database query
     *
     * @param Select $select
     * @param string|array $sort
     * @return Select
     */
    public function setSortOrder(Select $select, $sort)
    {
        if ($sort === '' || null === $sort || empty($sort)) {
            return $select;
        }

        $select->reset('order');

        if (is_string($sort)) {
            $sort = explode(' ', $sort);
        }

        $order = [];

        foreach ($sort as $column) {
            if (strchr($column, '-')) {
                $column = substr($column, 1, strlen($column));
                $direction = Select::ORDER_DESCENDING;
            } else {
                $direction = Select::ORDER_ASCENDING;
            }

            // COLLATE NOCASE
            // fix the sort order to make case insensitive for sqlite database.
            if ('sqlite' == $this->getAdapter()->getPlatform()->getName()) {
                $direction = 'COLLATE NOCASE ' . $direction;
            }

            $order[] = $column . ' ' . $direction;
        }

        return $select->order($order);
    }

    /**
     * @return Sql
     */
    protected function getSql()
    {
        if (!$this->sql) {
            $this->sql = new Sql($this->getAdapter());
        }

        return $this->sql;
    }

    /**
     * @return \Laminas\Db\Adapter\Adapter
     */
    public function getAdapter()
    {
        return $this->adapter;
    }

    /**
     * @return string
     */
    public function getTable()
    {
        return $this->table;
    }

    /**
     * @return string
     */
    public function getPrimaryKey()
    {
        return $this->primary;
    }

    /**
     * @param Select $select
     * @return string $sqlString
     */
    public function getSqlString($select)
    {
        $adapterPlatform = $this->getAdapter()->getPlatform();
        $sqlString = $select->getSqlString($adapterPlatform);
        \ChromePhp::info($sqlString);

        return $sqlString;
    }

    /**
     * @return boolean
     */
    public function isMysql57Compatible()
    {
        return $this->mysql57Compatible;
    }

    /**
     * @param boolean $mysql57Compatible
     * @return $this
     */
    public function setMysql57Compatible($mysql57Compatible)
    {
        $this->mysql57Compatible = $mysql57Compatible;
        return $this;
    }
}
