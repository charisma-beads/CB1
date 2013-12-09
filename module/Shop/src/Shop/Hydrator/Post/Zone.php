<?php
namespace Shop\Hydrator\Zone;

use Application\Hydrator\AbstractHydrator;

class Zone extends AbstractHydrator
{
    protected $prefix = 'postZone.';
    
    /**
     * @param \Shop\Model\Post\Zone $object
     * @return array $data
     */
    public function extract($object)
    {
        return array(
        	'postZoneId'   => $object->getPostZoneId(),
            'taxCodeId'    => $object->getTaxCodeId(),
            'zone'         => $object->getZone(),
        );
    }
}