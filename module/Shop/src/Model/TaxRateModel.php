<?php

namespace Shop\Model;

use Common\Model\Model;
use Common\Model\ModelInterface;

/**
 * Class Rate
 *
 * @package Shop\Model
 */
class TaxRateModel implements ModelInterface
{
    use Model;
    
	/**
	 * @var int
	 */
	protected $taxRateId;
	
	/**
	 * @var float
	 */
	protected $taxRate;

    /**
     * @return int
     */
    public function getTaxRateId()
	{
		return $this->taxRateId;
	}

    /**
     * @param $taxRateId
     * @return $this
     */
    public function setTaxRateId($taxRateId)
	{
		$this->taxRateId = $taxRateId;
		return $this;
	}

    /**
     * @param bool $formatPercent
     * @return float
     */
    public function getTaxRate($formatPercent=false)
	{
		return (true === $formatPercent) ? 1 + ($this->taxRate / 100) : $this->taxRate;
	}

    /**
     * @param $taxRate
     * @return $this
     */
    public function setTaxRate($taxRate)
	{
		$this->taxRate = $taxRate;
		return $this;
	}
}
