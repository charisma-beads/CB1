<?php
namespace Application\Model\DbTable;

class SessionTable extends AbstractTable
{
	protected $table = 'session';
	protected $primary = 'id';
	protected $rowClass = 'Application\Model\Entity\SessionEntity';
	
	public function fetchAllSessions(array $post)
	{
		$count = (isset($post['count'])) ? (int) $post['count'] : null;
		$offset = (isset($post['offset'])) ? (int) $post['offset'] : null;
		$sort = (isset($post['sort'])) ? (string) $post['sort'] : '';
		$page = (isset($post['page'])) ? (int) $post['page'] : null;
	
		$select = $this->sql->select();
		$select->from('session');
	
		$select = $this->setSortOrder($select, $sort);
	
		$resultSet = $this->tableGateway->getResultSetPrototype();
		$statement = $this->sql->prepareStatementForSqlObject($select);
		$result = $statement->execute();
	
		$resultSet->initialize($result);
		$resultSet->buffer();
		 
		if (null === $page) {
			return $resultSet;
		} else {
			return $this->paginate($resultSet, $page, $count);
		}
	}
}
