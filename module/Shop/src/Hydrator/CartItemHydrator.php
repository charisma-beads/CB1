<?php

namespace Shop\Hydrator;

use Common\Hydrator\AbstractHydrator;
use Shop\Hydrator\Strategy\Serialize;
use Shop\Hydrator\Strategy\PercentStrategy;

/**
 * Class Item
 *
 * @package Shop\Hydrator
 */
class CartItemHydrator extends AbstractHydrator
{
    public Function __construct()
    {
        parent::__construct();
        
        $this->addStrategy('metadata', new Serialize());
        $this->addStrategy('tax', new PercentStrategy());
    }
    
    /**
     * @param \Shop\Model\CartItemModel $object
     * @return array $data
     */
    public function extract($object)
    {
        return [
        	'cartItemId'    => $object->getCartItemId(),
            'cartId'        => $object->getCartId(),
            'quantity'      => $object->getQuantity(),
            'price'         => $object->getPrice(),
            'tax'           => $this->extractValue('tax', $object->getTax()),
            'metadata'      => $this->extractValue('metadata', $object->getMetadata()),
        ];
    }
}
