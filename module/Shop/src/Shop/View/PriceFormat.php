<?php
namespace Shop\View;

use Application\View\AbstractViewHelper;
use Zend\I18n\View\Helper\CurrencyFormat;

class PriceFormat extends AbstractViewHelper
{
    protected $currencyHelper;
    
    public function __invoke($amount)
    {
    	$currency = $this->getCurrencyHelper();
    	return $currency($amount);
    }
    
    /**
     * @return CurrencyFormat
     */
    protected function getCurrencyHelper()
    {
    	if (!$this->currencyHelper instanceof CurrencyFormat) {
    		$this->currencyHelper = $this->view->plugin('currencyFormat')
    		->setCurrencyCode("GBP")->setLocale("en_GB");
    	}
    
    	return $this->currencyHelper;
    }
}