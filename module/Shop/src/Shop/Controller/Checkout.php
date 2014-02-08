<?php
namespace Shop\Controller;

use User\Form\Login as LoginForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class Checkout extends AbstractActionController
{
    /**
     * @var \Shop\Service\Cart
     */
    protected $cartService;
    
    /**
     * @var \Shop\Service\CustomerAddress
     */
    protected $customerAddressService;
    
	public function indexAction()
	{
	    if (!$this->getCartService()->getCart()->count()) {
	        return $this->redirect()->toRoute('shop');
	    }
	    
		if ($this->identity()) {
			return $this->redirect()->toRoute('shop/checkout', array(
				'action' => 'confirm-address'
			));
		}
		
		return new ViewModel(array(
			'login'       => $this->getLoginForm(),
		    'register'    => $this->getRegisterForm()
		));
	}
	
	public function confirmAddressAction()
	{
		return new ViewModel();
	}
	
	public function confirmOrderAction()
	{
	    $countryId = $this->getCustomerAddressService()
            ->getDeliveryAddress(
                $this->identity()->getUserId()
		)->getCountryId();
	    
	    return new ViewModel(array(
			'countryId' => $countryId
	    ));
	}
	
	public function processOrderAction()
	{
	    // this is where we add the order to the database,
	    // send email order, offer a printable version of order
	    // and redirect to payment choice.
	}
	
	/**
	 * @return \Shop\Service\CustomerAddress
	 */
	public function getCustomerAddressService()
	{
	    if (!$this->customerAddressService) {
	    	$sl = $this->getServiceLocator();
	    	$this->customerAddressService = $sl->get('Shop\Service\CustomerAddress');
	    }
	    
	    return $this->customerAddressService;
	}
	
	/**
	 * @return \Shop\Service\Cart
	 */
	protected function getCartService()
	{
	    if (!$this->cartService) {
	        $sl = $this->getServiceLocator();
	        $this->cartService = $sl->get('Shop\Service\Cart');
	    }
	
	    return $this->cartService;
	}
	
	public function getLoginForm()
	{
	    $form = new LoginForm();
	    $form->setData(array('returnTo' => 'shop/checkout'));
	    return $form;
	}
	
	public function getRegisterForm()
	{
	    $userService = $this->getServiceLocator()->get('User\Service\User');
	    return $userService->getForm(null, array('returnTo' => 'shop/checkout'));
	}
}