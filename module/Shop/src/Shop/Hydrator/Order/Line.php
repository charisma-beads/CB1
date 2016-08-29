<?php
/**
 * Uthando CMS (http://www.shaunfreeman.co.uk/)
 *
 * @package   Shop\Hydrator\Order
 * @author    Shaun Freeman <shaun@shaunfreeman.co.uk>
 * @copyright Copyright (c) 2014 Shaun Freeman. (http://www.shaunfreeman.co.uk)
 * @license   see LICENSE.txt
 */

namespace Shop\Hydrator\Order;

use UthandoCommon\Hydrator\AbstractHydrator;
use UthandoCommon\Hydrator\Strategy\Serialize;
use Shop\Hydrator\Strategy\Percent;

/**
 * Class Line
 *
 * @package Shop\Hydrator\Order
 */
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
        return [
            'orderLineId'   => $object->getOrderLineId(),
            'orderId'       => $object->getOrderId(),
            'qty'           => $object->getQuantity(),
            'price'         => $object->getPrice(),
            'tax'           => $this->extractValue('tax', $object->getTax()),
            'metadata'      => $this->extractValue('metadata', $object->getMetadata()),
        ];
    }
}
