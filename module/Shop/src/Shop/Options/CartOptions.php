<?php
/**
 * Uthando CMS (http://www.shaunfreeman.co.uk/)
 *
 * @package   Shop\Options
 * @author    Shaun Freeman <shaun@shaunfreeman.co.uk>
 * @copyright Copyright (c) 2014 Shaun Freeman. (http://www.shaunfreeman.co.uk)
 * @license   see LICENSE.txt
 */

namespace Shop\Options;

use Zend\Stdlib\AbstractOptions;

/**
 * Class CartOptions
 *
 * @package Shop\Options
 */
class CartOptions extends AbstractOptions
{
    /**
     * @var boolean
     */
    protected $payCheck;
    
    /**
     * @var boolean
     */
    protected $collectInstore;
    
    /**
     * @var boolean
     */
    protected $payCreditCard;
    
    /**
     * @var boolean
     */
    protected $payPhone;
    
    /**
     * @var boolean
     */
    protected $payPaypal;
    
    /**
     * @var string
     */
    protected $creditCardPaymentEmail;
    
    /**
     * @var string
     */
    protected $orderEmail;
    
    /**
     * @var bool
     */
    protected $sendOrderToAdmin;

    /**
     * @var bool
     */
    protected $emailCustomerOnStatusChange;
    
	/**
	 * @return boolean $PayCheck
	 */
	public function getPayCheck()
	{
		return $this->payCheck;
	}

    /**
     * @param boolean $payCheck
     * @return $this
     */
	public function setPayCheck($payCheck)
	{
		$this->payCheck = $payCheck;
		return $this;
	}

	/**
	 * @return boolean $CollectInstore
	 */
	public function getCollectInstore()
	{
		return $this->collectInstore;
	}

    /**
     * @param boolean $collectInstore
     * @return $this
     */
	public function setCollectInstore($collectInstore)
	{
		$this->collectInstore = $collectInstore;
		return $this;
	}

	/**
	 * @return boolean $PayCreditCard
	 */
	public function getPayCreditCard()
	{
		return $this->payCreditCard;
	}

    /**
     * @param $payCreditCard
     * @return $this
     */
	public function setPayCreditCard($payCreditCard)
	{
		$this->payCreditCard = $payCreditCard;
		return $this;
	}

	/**
	 * @return boolean $PayPhone
	 */
	public function getPayPhone()
	{
		return $this->payPhone;
	}

    /**
     * @param $payPhone
     * @return $this
     */
	public function setPayPhone($payPhone)
	{
		$this->payPhone = $payPhone;
		return $this;
	}

	/**
	 * @return boolean $PayPaypal
	 */
	public function getPayPaypal()
	{
		return $this->payPaypal;
	}

    /**
     * @param $payPaypal
     * @return $this
     */
	public function setPayPaypal($payPaypal)
	{
		$this->payPaypal = $payPaypal;
		return $this;
	}
    
    /**
     * @return string $creditCardPaymentEmail
     */
    public function getCreditCardPaymentEmail()
    {
        return $this->creditCardPaymentEmail;
    }

    /**
     * @param string $creditCardPaymentEmail
     * @return $this
     */
    public function setCreditCardPaymentEmail($creditCardPaymentEmail)
    {
        $this->creditCardPaymentEmail = $creditCardPaymentEmail;
        return $this;
    }

    /**
     * @return string $orderEmail
     */
    public function getOrderEmail()
    {
        return $this->orderEmail;
    }

    /**
     * @param string $orderEmail
     * @return $this
     */
    public function setOrderEmail($orderEmail)
    {
        $this->orderEmail = $orderEmail;
        return $this;
    }
 
    /**
     * @return boolean $sendOrderToAdmin
     */
    public function getSendOrderToAdmin()
    {
        return $this->sendOrderToAdmin;
    }

    /**
     * @param boolean $sendOrderToAdmin
     * @return $this
     */
    public function setSendOrderToAdmin($sendOrderToAdmin)
    {
        $this->sendOrderToAdmin = $sendOrderToAdmin;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isEmailCustomerOnStatusChange()
    {
        return $this->emailCustomerOnStatusChange;
    }

    /**
     * @param boolean $emailCustomerOnStatusChange
     * @return $this
     */
    public function setEmailCustomerOnStatusChange($emailCustomerOnStatusChange)
    {
        $this->emailCustomerOnStatusChange = (bool) $emailCustomerOnStatusChange;
        return $this;
    }
}