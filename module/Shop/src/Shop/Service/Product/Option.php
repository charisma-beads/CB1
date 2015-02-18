<?php

namespace Shop\Service\Product;

use UthandoCommon\Service\AbstractRelationalMapperService;

class Option extends AbstractRelationalMapperService
{
    /**
     * @var string
     */
    protected $serviceAlias = 'ShopProductOption';

    protected $referenceMap = [
        'product'   => [
            'refCol'    => 'productId',
            'service'   => 'ShopProduct',
        ],
        'postUnit'          => [
            'refCol'    => 'postUnitId',
            'service'   => 'ShopPostUnit',
        ],
    ];

    /**
     * @param $id
     * @return \Zend\Db\ResultSet\HydratingResultSet
     */
    public function getOptionsByProductId($id)
    {
        $id = (int) $id;

        /* @var $mapper \Shop\Mapper\Product\Option */
        $mapper = $this->getMapper();
        $options = $mapper->getOptionsByProductId($id);

        foreach ($options as $row) {
            $this->populate($row, ['postUnit']);
        }


        return $options;
    }
} 