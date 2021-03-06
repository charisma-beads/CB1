<?php

namespace Shop\Model;

use Common\Model\ModelInterface;
use Common\Model\Model;
use Common\Model\DateCreatedTrait;

/**
 * Class Hit
 *
 * @package Shop\Model
 */
class AdvertHitModel implements ModelInterface
{
    use Model,
        DateCreatedTrait;
    
    /**
     * @var int
     */
    protected $advertHitId;
    
    /**
     * @var int
     */
    protected $advertId;
 
    /**
     * @return int $advertHitId
     */
    public function getAdvertHitId()
    {
        return $this->advertHitId;
    }

    /**
     * @param int $advertHitId
     * @return $this
     */
    public function setAdvertHitId($advertHitId)
    {
        $this->advertHitId = $advertHitId;
        return $this;
    }

    /**
     * @return int $advertId
     */
    public function getAdvertId()
    {
        return $this->advertId;
    }

     /**
     * @param int $advertId
     * @return $this
     */
    public function setAdvertId($advertId)
    {
        $this->advertId = $advertId;
        return $this;
    }
}
