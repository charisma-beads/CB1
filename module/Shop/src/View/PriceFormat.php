<?php

namespace Shop\View;

use Common\View\AbstractViewHelper;
use Laminas\I18n\View\Helper\CurrencyFormat;

/**
 * Class PriceFormat
 *
 * @package Shop\View
 */
class PriceFormat extends AbstractViewHelper
{
    protected $currencyHelper;
    
    public function __invoke($amount)
    {
    	$currency = $this->getCurrencyHelper()
    		->setCurrencyCode("GBP")
    		->setLocale("en_GB");
    	
    	return $currency($amount);
    }
    
    /**
     * @return CurrencyFormat
     */
    protected function getCurrencyHelper()
    {
    	if (!$this->currencyHelper instanceof CurrencyFormat) {
    		$this->currencyHelper = $this->view->plugin('currencyFormat');
    	}
    
    	return $this->currencyHelper;
    }
}
