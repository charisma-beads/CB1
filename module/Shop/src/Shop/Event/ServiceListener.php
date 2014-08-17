<?php
namespace Shop\Event;

use Zend\EventManager\Event;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\EventManager\ListenerAggregateTrait;

class ServiceListener implements ListenerAggregateInterface
{
    use ListenerAggregateTrait;
    
    public function attach(EventManagerInterface $events)
    {
        $events = $events->getSharedManager();
        
        $this->listeners[] = $events->attach([
            'Shop\Service\Customer',
            'Shop\Service\Customer\Address',
        ], 'form.init', [$this, 'formInit']);
        
        $this->listeners[] = $events->attach([
            'Shop\Service\Product'
        ], ['pre.add', 'pre.edit'], [$this, 'setProductIdent']);
        
        $this->listeners[] = $events->attach([
    		'Shop\Service\Customer\Address'
		], ['pre.add', 'pre.edit'], [$this, 'setCountryValidation']);
    }
    
    public function setProductIdent(Event $e)
    {
        $post = $e->getParam('post');
        
        if (!$post['ident']) {
        	$post['ident'] = $post['name'] . ' ' . $post['shortDescription'];
        }
        
        $e->setParam('post', $post);
    }
    
    public function setCountryValidation(Event $e)
    {
        $form = $e->getParam('form');
        $post = $e->getParam('post');
        $model = $e->getParam('model');
        
        /* @var $service \Shop\Service\Country */
        $service = $e->getTarget()->getCountryService();
        $countryId = $post['countryId'];
        
        $country = $service->getById($countryId);
        
        $validator = $form->getInputFilter();
        
        $phone = $validator->get('phone')->getValidatorChain()->getValidators()[0]['instance'];
        $postcode = $validator->get('postcode')->getValidatorChain()->getValidators()[0]['instance'];
        
        $phone->setCountry($country->getCode());
        $postcode->setCountry($country->getCode());
    }
    
    public function formInit(Event $e)
    {
        $form = $e->getParam('form');
        $data = $e->getParam('data');
        $model = $e->getParam('model');
        
        switch (get_class($model)) {
        	case 'Shop\Model\Customer':
        	    $this->customerForm($form, $model);
        	    break;
        	case 'Shop\Model\Customer\Address':
        	    $this->customerAddressForm($form, $model, $data);
        	    break;
        }
    }
    
    public function customerForm($form, $model)
    {
    	$form->get('billingAddressId')->setCustomerId($model->getCustomerId());
    	$form->get('deliveryAddressId')->setCustomerId($model->getCustomerId());
    }
    
    public function customerAddressForm($form, $model, $data)
    {
        if (isset($data['countryId'])) {
        	$countryId = $data['countryId'];
        } elseif ($model) {
        	$countryId = $model->getCountryId();
        } else {
        	$countryId = $form->get('countryId')->getCountryId();
        }
         
        $form->get('provinceId')->setCountryId($countryId);
    }
}
