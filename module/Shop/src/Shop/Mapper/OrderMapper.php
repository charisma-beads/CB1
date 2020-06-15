<?php
/**
 * Uthando CMS (http://www.shaunfreeman.co.uk/)
 *
 * @package   Shop\Mapper
 * @author    Shaun Freeman <shaun@shaunfreeman.co.uk>
 * @copyright Copyright (c) 2014 Shaun Freeman. (http://www.shaunfreeman.co.uk)
 * @license   see LICENSE.txt
 */

namespace Shop\Mapper;

use Common\Mapper\AbstractDbMapper;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Expression;
use Zend\Db\Sql\Select;

/**
 * Class Order
 *
 * @package Shop\Mapper
 */
class OrderMapper extends AbstractDbMapper
{
    protected $table = 'order';
    protected $primary = 'orderId';

    /**
     * @return \Zend\Db\ResultSet\HydratingResultSet|ResultSet|\Zend\Paginator\Paginator
     */
    public function getCurrentOrders()
    {
        $select = $this->getSql()->select($this->table);
        $select->join(
            'orderStatus',
            'order.orderStatusId=orderStatus.orderStatusId',
            []
        )->where
            ->notEqualTo('orderStatus', 'Cancelled')
            ->notEqualTo('orderStatus', 'Dispatched')
            ->notEqualTo('orderStatus', 'Acknowledged')
            ->notEqualTo('orderStatus', 'Ready For Collection');

        $select     = $this->setSortOrder($select, ['-orderNumber']);
        $resultSet  = $this->fetchResult($select);

        return $resultSet;
    }

    /**
     * @param $id
     * @return \Zend\Db\ResultSet\HydratingResultSet|ResultSet|\Zend\Paginator\Paginator
     */
    public function getOrdersByCustomerId($id)
    {
        $select = $this->getSelect();
        $select->where->equalTo('customerId', $id);
        $resultSet = $this->fetchResult($select);

        return $resultSet;
    }

    /**
     * @return array|\ArrayObject|null|object
     */
    public function getMinMaxYear()
    {
        $select = $this->getSelect();
        $select->columns([
            'minYear' => new Expression("MIN(DATE_FORMAT(order.orderDate, '%Y'))"),
            'maxYear' => new Expression("MAX(DATE_FORMAT(order.orderDate, '%Y'))"),
        ]);

        $rowSet = $this->fetchResult($select, new ResultSet());
        $row = $rowSet->current();

        return $row;
    }

    /**
     * @param null|string $startDate
     * @param null|string $endDate
     * @return \Zend\Db\ResultSet\HydratingResultSet|ResultSet|\Zend\Paginator\Paginator
     */
    public function getMonthlyTotals($startDate = null, $endDate = null)
    {
        $select = $this->getSelect();
        $select->columns([
            'numOrders'     => new Expression('COUNT(order.orderId)'),
            'total'         => new Expression('SUM(order.total)'),
            'month'         => new Expression("DATE_FORMAT(order.orderDate, '%m')"),
            'year'          => new Expression("DATE_FORMAT(order.orderDate, '%Y')"),
        ])->join(
            'orderStatus',
            'order.orderStatusId=orderStatus.orderStatusId',
            []
        )->group([
            'year', 'month'
        ])->order([
            'year ' . Select::ORDER_ASCENDING,
            'month ' . Select::ORDER_ASCENDING,
        ])->where->notEqualTo(
            'orderStatus', 'Cancelled'
        );

        if ($startDate && $endDate) {
            $select->where->between('order.orderDate', $startDate, $endDate);
        }

        return $this->fetchResult($select, new ResultSet());
    }

    /**
     * @param $orderNumber
     * @return null|\Shop\Model\OrderModel
     */
    public function getOrderByOrderNumber($orderNumber)
    {
        $select = $this->getSelect();
        $select->where->equalTo('orderNumber', $orderNumber);
        $resultSet = $this->fetchResult($select);

        return $resultSet->current();
    }

    /**
     * @return array|\ArrayObject|null|object
     */
    public function getMaxOrderNumber()
    {
        $select = $this->getSelect();
        $select->columns([
        	'orderNumber' => new Expression('(MAX(orderNumber))')
        ]);
        
        $resultSet = $this->fetchResult($select, new ResultSet());
        $row = $resultSet->current();
        
        return $row;
    }

    /**
     * @param $id
     * @param $userId
     * @return null|\Shop\Model\OrderModel
     */
    public function getOrderByUserId($id, $userId)
    {
        $select = $this->getCustomerOrderSelect();
        $select->where
            ->equalTo('userId', $userId)
            ->and->equalTo('orderId', $id);
        
        $resultSet = $this->fetchResult($select);
        $row = $resultSet->current();
        
        return $row;
    }

    /**
     * @param $id
     * @return \Zend\Db\ResultSet\HydratingResultSet|ResultSet|\Zend\Paginator\Paginator
     */
    public function getOrdersByUserId($id)
    {
        $select = $this->getCustomerOrderSelect();
        $select->where->equalTo('userId', $id);
        $select = $this->setSortOrder($select, ['-orderDate']);
        
        return $this->fetchResult($select);
    }

    /**
     * @return Select
     */
    public function getCustomerOrderSelect()
    {
        $select = $this->getSql()->select($this->table);
        $select->join(
            'customer',
            'order.customerId=customer.customerId',
            []
        );
        
        return $select;
    }
}
