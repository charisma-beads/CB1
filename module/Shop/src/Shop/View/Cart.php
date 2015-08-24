<?php
/**
 * Uthando CMS (http://www.shaunfreeman.co.uk/)
 *
 * @package   Shop\View
 * @author    Shaun Freeman <shaun@shaunfreeman.co.uk>
 * @link      https://github.com/uthando-cms for the canonical source repository
 * @copyright Copyright (c) 2014 Shaun Freeman. (http://www.shaunfreeman.co.uk)
 * @license   see LICENSE.txt
 */

namespace Shop\View;

use Shop\Form\Cart\Add;
use Shop\Model\Cart\Cart as CartModel;
use UthandoCommon\View\AbstractViewHelper;
use Zend\I18n\View\Helper\CurrencyFormat;
use Shop\Service\Cart\Cart as CartService;
use Zend\View\Model\ViewModel;

/**
 * Class Cart
 *
 * @package Shop\View
 */
class Cart extends AbstractViewHelper
{
	/**
	 * @var \Shop\Service\Cart\Cart
	 */
	protected $cartService;
	
	/**
	 * @var CurrencyFormat
	 */
	protected $currencyHelper;

    /**
     * @return $this
     */
    public function __invoke()
	{
		if (!$this->cartService instanceof CartService) {
			$this->cartService = $this->getServiceLocator()
				->getServiceLocator()
                ->get('UthandoServiceManager')
                ->get('ShopCart');
		}
		
		return $this;
	}
	
	/**
	 * @return CartModel
	 */
	public function getCart()
	{
		return $this->cartService->getCart();
	}

    /**
     * @return int
     */
	public function countItems()
	{
        $count = 0;

        if ($this->getCart() instanceof CartModel) {
            $count = $this->getCart()
                ->count();
        }

		return $count;
	}

    /**
     * @param $template
     * @return string
     */
	public function getSummary($template)
	{
		$view = new ViewModel();
		$view->setTemplate($template);
		
		return $this->getView()->render($view);
	}

    /**
     * @param $item
     * @return string
     */
	public function getLineCost($item)
	{
	    $amount = $this->cartService->getLineCost($item);
	    return $this->formatAmount($amount);
	}

    /**
     * @return string
     */
	public function getSubTotal()
	{
	    $amount = $this->cartService->getSubTotal();
	    return $this->formatAmount($amount);
	}

    /**
     * @return string
     */
	public function getTotal()
	{
		$amount = $this->cartService->getTotal();
		return $this->formatAmount($amount);
	}

    /**
     * @param $countryId
     * @return string
     */
	public function getShippingTotal($countryId)
	{
	    $this->cartService->setShippingCost($countryId);
	    return $this->formatAmount($this->cartService->getShippingCost());
	}

    /**
     * @param $product
     * @return string
     */
	public function hasProductInCart($product)
	{
	    $items = $this->getCart()->getEntities();
        $numItems = null;

	    foreach ($items as $item) {
	        if ($item->getMetaData()->getProductId() == $product->getProductId()) {
	            $numItems += $item->getQuantity();
	        }
	    }

        if ($numItems) {
            return '&nbsp;(' . $numItems . ')';
        }
	    
	    return '';
	}

    /**
     * @param $amount
     * @return string
     */
	public function formatAmount($amount)
    {
        $currency = $this->getCurrencyHelper();
        return $currency($amount);
    }

    /**
     * @param $product
     * @return Add
     */
	public function addForm($product)
	{
		$form = new Add();
		$form->init();
		
		$form->setData(array(
			'productId' => $product->getProductId(),
			'returnTo'  => $this->view->serverUrl(true)
		));
		
		$form->setAttributes(array(
			'action' =>  $this->view->url('shop/cart', [
				'action'   => 'add'
			]),
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