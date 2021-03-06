<?php

namespace Common\Db\Table;

use Common\Hydrator\BaseHydrator;
use Common\Model\ModelInterface;
use Common\UthandoException;
use Laminas\Db\Adapter\Adapter;
use Laminas\Db\ResultSet\HydratingResultSet;
use Laminas\Db\ResultSet\ResultSet;
use Laminas\Db\TableGateway\TableGateway;
use Laminas\ServiceManager\AbstractFactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;


class AbstractTableFactory implements AbstractFactoryInterface
{
    /**
     * @var array
     */
    protected $tableNamesMap;

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @param $name
     * @param $requestedName
     * @return bool
     */
    public function canCreateServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        return (fnmatch('*Table', $requestedName)) ? true : false;
    }

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @param $name
     * @param $requestedName
     * @return bool
     */
    public function createServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        $config = $serviceLocator->get('config');
        $this->tableNamesMap = (isset($config['db_table_names_map'])) ? $config['db_table_names_map'] : null;

        if (class_exists($requestedName) && is_array($this->tableNamesMap) && array_key_exists($requestedName, $this->tableNamesMap)) {

            /* @var \Laminas\Db\Adapter\Adapter $dbAdapter */
            $dbAdapter          = $serviceLocator->get(Adapter::class);
            $resultSetPrototype = $this->getHydrator($requestedName);
            $tableGateway       = new TableGateway($this->tableNamesMap[$requestedName], $dbAdapter, null, $resultSetPrototype);
            /* @var AbstractTable $table */
            $table              = new $requestedName();

            $table->setTableGateway($tableGateway);

            return $table;
        }

        return false;
    }

    /**
     * @param $requestedName
     * @param $hydratorOrModel
     * @return null|BaseHydrator|ModelInterface
     */
    public function getHydratorOrModel($requestedName, $hydratorOrModel)
    {
        $class = str_replace('Db\Table', $hydratorOrModel, $requestedName);
        $class = str_replace('Table', $hydratorOrModel, $class);

        return (class_exists($class)) ? new $class() : null;
    }

    /**
     * @param $requestedName
     * @return null|object
     * @throws UthandoException
     */
    public function getModel($requestedName)
    {
        $model  = $this->getHydratorOrModel($requestedName, 'Entity');

        if (null === $model) {
            throw new UthandoException('Entity class:' .$requestedName .' does not exist');
        }

        return $model;
    }

    /**
     * @param $requestedName
     * @return HydratingResultSet|ResultSet
     */
    public function getHydrator($requestedName)
    {
        $hydrator  = $this->getHydratorOrModel($requestedName, 'Hydrator');

        if ($hydrator) {
            $hydrator->setTablePrefix($this->tableNamesMap[$requestedName]);
            $resultSetPrototype = new HydratingResultSet();
            $resultSetPrototype->setHydrator($hydrator);
            $resultSetPrototype->setObjectPrototype($this->getModel($requestedName));
        } else {
            $resultSetPrototype = new ResultSet();
        }

        return $resultSetPrototype;

    }
}
