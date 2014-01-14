<?php
namespace Shop\Hydrator\Product;

use Application\Hydrator\AbstractHydrator;

class GroupPrice extends AbstractHydrator
{
    protected $prefix = 'productGroupPrice.';
    
    /**
     * @param \Shop\Model\Product\GroupPrice $object
     * @return array
     */
    public function extract($object)
    {
        return array(
        	'productGroupId'   => $object->getProductGroupId(),
            'group'            => $object->getGroup(),
            'price'            => $object->getPrice(),
        );
    }
}