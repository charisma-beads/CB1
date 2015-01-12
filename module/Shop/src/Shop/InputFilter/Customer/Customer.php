<?php
namespace Shop\InputFilter\Customer;

use Zend\InputFilter\InputFilter;

class Customer extends InputFilter
{
    public function init()
    {
        $this->add([
            'name' => 'customerId',
            'required' => false,
            'filters' => [
                ['name' => 'StripTags'],
                ['name' => 'StringTrim'],
            ],
            'validators' => [
                ['name' => 'Int'],
            ],
        ]);
        
        $this->add([
            'name' => 'userId',
            'required' => false,
            'filters' => [
                ['name' => 'StripTags'],
                ['name' => 'StringTrim'],
            ],
            'validators' => [
                ['name' => 'Int']
            ],
        ]);
        
        $this->add([
    		'name' => 'prefixId',
    		'required' => true,
    		'filters' => [
        		['name' => 'StripTags'],
        		['name' => 'StringTrim'],
    		],
    		'validators' => [
        		['name' => 'Int'],
                ['name' => 'GreaterThan', 'options' => [
                    'min' => 0,
                ]],
    		],
		]);
        
        $this->add([
    		'name' => 'billingAddressId',
    		'required' => true,
    		'filters' => [
        		['name' => 'StripTags'],
        		['name' => 'StringTrim'],
    		],
    		'validators' => [
        		['name' => 'Int'],
                ['name' => 'GreaterThan', 'options' => [
                    'min' => 0,
                ]],
    		],
		]);
        
        $this->add([
    		'name' => 'deliveryAddressId',
    		'required' => true,
    		'filters' => [
        		['name' => 'StripTags'],
        		['name' => 'StringTrim'],
    		],
    		'validators' => [
        		['name' => 'Int'],
                ['name' => 'GreaterThan', 'options' => [
            		'min' => 0,
                ]],
    		],
		]);

        $this->add([
            'name' => 'email',
            'required' => false,
            'filters' => [
                ['name' => 'StripTags'],
                ['name' => 'StringTrim'],
            ],
            'validators' => [

            ],
        ]);
    }
}