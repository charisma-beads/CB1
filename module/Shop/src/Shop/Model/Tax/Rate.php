<?php
namespace Shop\Model\Tax;

use UthandoCommon\Model\Model;
use UthandoCommon\Model\ModelInterface;

class Rate implements ModelInterface
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
	 * @return the $taxRateId
	 */
	public function getTaxRateId()
	{
		return $this->taxRateId;
	}

	/**
	 * @param number $taxRateId
	 */
	public function setTaxRateId($taxRateId)
	{
		$this->taxRateId = $taxRateId;
		return $this;
	}

	/**
	 * @return the $taxRate
	 */
	public function getTaxRate($formatPercent=false)
	{
		return (true === $formatPercent) ? $this->taxRate / 100 : $this->taxRate;
	}

	/**
	 * @param number $taxRate
	 */
	public function setTaxRate($taxRate)
	{
		$this->taxRate = $taxRate;
		return $this;
	}
}
