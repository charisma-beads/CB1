<?php

namespace Shop\Hydrator;

use Common\Hydrator\AbstractHydrator;
use Common\Hydrator\Strategy\DateTime as DateTimeStrategy;

/**
 * Class Hit
 *
 * @package Shop\Hydrator
 */
class AdvertHitHydrator extends AbstractHydrator
{
    public function __construct()
    {
        parent::__construct();

        $this->addStrategy('dateCreated', new DateTimeStrategy());
    }
    
    /**
     * @param \Shop\Model\AdvertHitModel $object
     * @return array
     */
    public function extract($object)
    {
        return [
            'advertHitId'   => $object->getAdvertHitId(),
            'advertId'      => $object->getAdvertId(),
            'dateCreated'   => $this->extractValue('dateCreated', $object->getDateCreated()),
        ];
    }
}
