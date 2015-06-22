<?php
namespace Shop\Hydrator\Order;

use UthandoCommon\Hydrator\AbstractHydrator;
use UthandoCommon\Hydrator\Strategy\Serialize;
use Shop\Hydrator\Strategy\Percent;

class Line extends AbstractHydrator
{
    public function __construct()
    {
        parent::__construct();
        
        $this->addStrategy('metadata', new Serialize());
        $this->addStrategy('tax', new Percent());
    }
    
    /**
     *
     * @param \Shop\Model\Order\Line $object            
     * @return array $data
     */
    public function extract($object)
    {
        return array(
            'orderLineId'   => $object->getOrderLineId(),
            'orderId'       => $object->getOrderId(),
            'productId'     => $object->getProductId(),
            'qty'           => $object->getQty(),
            'price'         => $object->getPrice(),
            'tax'           => $this->extractValue('tax', $object->getTax()),
            'metadata'      => $this->extractValue('metadata', $object->getMetadata()),
        );
    }
}
