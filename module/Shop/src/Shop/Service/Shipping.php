<?php
/**
 * Uthando CMS (http://www.shaunfreeman.co.uk/)
 *
 * @package   Shop\Service
 * @author    Shaun Freeman <shaun@shaunfreeman.co.uk>
 * @copyright Copyright (c) 2014 Shaun Freeman. (http://www.shaunfreeman.co.uk)
 * @license   see LICENSE.txt
 */

namespace Shop\Service;

use Shop\Model\Order\AbstractOrderCollection;
use Shop\Model\Order\LineInterface;
use Shop\Service\Cart\Cart;
use Shop\Service\Country\Country;
use Shop\Service\Tax\Tax;

/**
 * Class Shipping
 *
 * @package Shop\Service
 */
class Shipping
{
    /**
     * @var Country
     */
    protected $countryService;

    /**
     * @var Tax
     */
    protected $taxService;

    /**
     * @var int
     */
    protected $countryId;

    /**
     * @var int
     */
    protected $postWeight = 0;

    /**
     * @var int
     */
    protected $noShipping = 0;

    /**
     * @var int
     */
    protected $shippingTax = 0;

    /**
     * @var int
     */
    protected $shippingTotal = 0;

    /**
     * @var bool
     */
    protected $shippingByWeight = false;

    /**
     * @param AbstractOrderCollection $orderModel
     * @return number
     */
    public function calculateShipping(AbstractOrderCollection $orderModel)
    {
        $this->noShipping = 0;
        $this->postWeight = 0;
        $this->shippingTax = 0;
        
        /* @var $item LineInterface */
        foreach ($orderModel as $item) {
            if ($item->getMetadata()->getAddPostage() === true) {
            	$this->postWeight += $item->getMetadata()->getPostUnit() * $item->getQuantity();
            } else {
            	$this->noShipping += $item->getPrice();
            }
        }

        if ($this->getShippingByWeight() === true) {
            $itemLevel = $this->getPostWeight();
        } else {
            $itemLevel = $orderModel->getSubTotal() - $this->getNoShipping();
        }

        if ($itemLevel == $this->getNoShipping()) {
            return 0;
        }
        
        $shippingLevels = $this->getCountryService()
            ->getCountryPostalRates($this->getCountryId());

        $taxInc = 0;
        $taxRate = 0;

        foreach ($shippingLevels as $row) {
        	if ($itemLevel > $row->postLevel) {
        	   $this->shippingTotal = $row->cost;
        	   $taxInc = $row->vatInc;
        	   $taxRate = $row->taxRate;
        	}
        }
        
        $taxService = $this->getTaxService()
            ->setTaxInc($taxInc);
        
        $taxService->addTax($this->shippingTotal, $taxRate);
        
        $this->setShippingTax($taxService->getTax());
        
        $price = $taxService->getPrice();
        $tax = $taxService->getTax();
        
        return $price;
    }

    /**
     * @return int
     */
    public function getCountryId()
	{
		return $this->countryId;
	}

    /**
     * @param $countryId
     * @return $this
     */
    public function setCountryId($countryId)
	{
		$this->countryId = $countryId;
		return $this;
	}

    /**
     * @return int
     */
    public function getPostWeight()
	{
		return $this->postWeight;
	}

    /**
     * @param $postWeight
     * @return $this
     */
    public function setPostWeight($postWeight)
	{
		$this->postWeight = $postWeight;
		return $this;
	}

    /**
     * @return int
     */
    public function getNoShipping()
	{
		return $this->noShipping;
	}

    /**
     * @param $noShipping
     * @return $this
     */
    public function setNoShipping($noShipping)
	{
		$this->noShipping = $noShipping;
		return $this;
	}

    /**
     * @return int
     */
    public function getShippingTax()
	{
		return $this->shippingTax;
	}

    /**
     * @param $shippingTax
     * @return $this
     */
    public function setShippingTax($shippingTax)
	{
		$this->shippingTax = $shippingTax;
		return $this;
	}

    /**
     * @return bool
     */
    public function getShippingByWeight()
	{
		return $this->shippingByWeight;
	}

    /**
     * @param $shippingByWeight
     * @return $this
     */
    public function setShippingByWeight($shippingByWeight)
	{
		$this->shippingByWeight = $shippingByWeight;
		return $this;
	}

    /**
     * @return Country
     */
    public function getCountryService()
    {
    	return $this->countryService;
    }

    /**
     * @param $countryService
     * @return $this
     */
    public function setCountryService($countryService)
    {
    	$this->countryService = $countryService;
    	return $this;
    }

    /**
     * @return Tax
     */
    public function getTaxService()
    {
    	return $this->taxService;
    }

    /**
     * @param $taxService
     * @return $this
     */
    public function setTaxService($taxService)
    {
    	$this->taxService = $taxService;
    	return $this;
    }
}
