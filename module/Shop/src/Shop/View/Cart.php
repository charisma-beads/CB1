<?php
namespace Shop\View;

use Application\View\AbstractViewHelper;
use Shop\Form\Cart\Add;
use Zend\I18n\View\Helper\CurrencyFormat;
use Shop\Service\Cart as CartService;
use Zend\View\Model\ViewModel;

class Cart extends AbstractViewHelper
{
	/**
	 * @var Shop\Service\Cart
	 */
	protected $cartService;
	
	/**
	 * @var CurrencyFormat
	 */
	protected $currencyHelper;
	
	public function __invoke()
	{
		if (!$this->cartService instanceof CartService) {
			$this->cartService = $this->getServiceLocator()
				->getServiceLocator()
				->get('Shop\Service\Cart');
		}
	
		return $this;
	}
	
	/**
	 * @return \Shop\Service\Cart
	 */
	public function getCart()
	{
		return $this->cartService;
	}
	
	public function countItems()
	{
		return count($this->cartService);
	}
	
	public function getSummary()
	{
		$view = new ViewModel();
		$view->setTemplate('cart/summary');
		
		return $this->getView()->render($view);
	}
	
	public function getLineCost($item)
	{
	    $amount = $this->getCart()->getLineCost($item);
	    return $this->formatAmount($amount);
	}
	
	public function getSubTotal()
	{
	    $amount = $this->getCart()->getSubTotal();
	    return $this->formatAmount($amount);
	}
	
	public function getShippingTotal()
	{
	    
	}
	
	public function formatAmount($amount)
    {
        $currency = $this->getCurrencyHelper();
        return $currency($amount);
    }
	
	public function addForm($product)
	{
		$form = new Add();
		
		$form->setData(array(
			'productId' => $product->productId,
			'returnTo'  => $this->view->serverUrl(true)
		));
		
		$form->setAttributes(array(
			'action' =>  $this->view->url('shop/cart', array(
				'action'   => 'add'
			)),
			'class' => 'form-search'
		));
	
		return $form;
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