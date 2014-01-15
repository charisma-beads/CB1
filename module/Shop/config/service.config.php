<?php

return array(
    'shared'        => array(
    	'Shop\Form\Country'						=> false,
        'Shop\Form\Customer\Address'            => false,
    	'Shop\Form\PostLevel'					=> false,
    	'Shop\Form\PostZone'					=> false,
    	'Shop\Form\Product'						=> false,
    	'Shop\Form\Product\Category'			=> false,
    ),
	'invokables'    => array(
		'Shop\Form\PostLevel'					=> 'Shop\Form\Post\Level',
		
		'Shop\InputFilter\Country'				=> 'Shop\InputFilter\Country',
		'Shop\InputFilter\PostLevel'			=> 'Shop\InputFilter\Post\Level',
	    'Shop\InputFilter\PostUnit'	            => 'Shop\InputFilter\Post\Unit',
		'Shop\InputFilter\PostZone'				=> 'Shop\InputFilter\Post\Zone',
	    'Shop\InputFilter\Product' 			    => 'Shop\InputFilter\Product',
		'Shop\InputFilter\ProductCategory'	    => 'Shop\InputFilter\Product\Category',
		'Shop\InputFilter\ProductImage'		    => 'Shop\InputFilter\Product\Image',
		'Shop\InputFilter\ProductOption'	    => 'Shop\InputFilter\Product\Option',
		'Shop\InputFilter\ProductSize'		    => 'Shop\InputFilter\Product\Size',
		'Shop\InputFilter\StockStatus'          => 'Shop\InputFilter\Stock\Status',
		'Shop\InputFilter\TaxCode'				=> 'Shop\InputFilter\Tax\Code',
		'Shop\InputFilter\TaxRate'				=> 'Shop\InputFilter\Tax\Rate',
	    
	    'Shop\Mapper\Country'                   => 'Shop\Mapper\Country',
	    'Shop\Mapper\Customer'                  => 'Shop\Mapper\Customer',
	    'Shop\Mapper\CustomerAddress'           => 'Shop\Mapper\Customer\Address',
	    'Shop\Mapper\CustomerPrefix'            => 'Shop\Mapper\Customer\Prefix',
	    'Shop\Mapper\Order'                     => 'Shop\Mapper\Order',
	    'Shop\Mapper\OrderLine'                 => 'Shop\Mapper\Order\Line',
	    'Shop\Mapper\OrderStatus'               => 'Shop\Mapper\Order\Status',
	    'Shop\Mapper\PostCost'                  => 'Shop\Mapper\Post\Cost',
	    'Shop\Mapper\PostLevel'                 => 'Shop\Mapper\Post\Level',
	    'Shop\Mapper\PostUnit'		            => 'Shop\Mapper\Post\Unit',
	    'Shop\Mapper\PostZone'                  => 'Shop\Mapper\Post\Zone',
		'Shop\Mapper\Product' 				    => 'Shop\Mapper\Product',
		'Shop\Mapper\ProductCategory'		    => 'Shop\Mapper\Product\Category',
	    'Shop\Mapper\ProductGroupPrice'         => 'Shop\Mapper\Product\GroupPrice',
		'Shop\Mapper\ProductImage'			    => 'Shop\Mapper\Product\Image',
		'Shop\Mapper\ProductOption'			    => 'Shop\Mapper\Product\Option',
		'Shop\Mapper\ProductSize'			    => 'Shop\Mapper\Product\Size',
		'Shop\Mapper\StockStatus'               => 'Shop\Mapper\Stock\Status',
		'Shop\Mapper\TaxCode'				    => 'Shop\Mapper\Tax\Code',
		'Shop\Mapper\TaxRate'				    => 'Shop\Mapper\Tax\Rate',
		
	    'Shop\Service\Country'                  => 'Shop\Service\Country',
	    'Shop\Service\Customer'                 => 'Shop\Service\Customer',
	    'Shop\Service\CustomerAddress'          => 'Shop\Service\Customer\Address',
	    'Shop\Service\CustomerPrefix'           => 'Shop\Service\Customer\Prefix',
	    'Shop\Service\Order'                    => 'Shop\Service\Order',
	    'Shop\Service\OrderLine'                => 'Shop\Service\Order\Line',
	    'Shop\Service\OrderStatus'              => 'Shop\Service\Order\Status',
	    'Shop\Service\PostCost'                 => 'Shop\Service\Post\Cost',
	    'Shop\Service\PostLevel'                => 'Shop\Service\Post\Level',
	    'Shop\Service\PostUnit'		            => 'Shop\Service\Post\Unit',
	    'Shop\Service\PostZone'                 => 'Shop\Service\Post\Zone',
		'Shop\Service\Product' 				    => 'Shop\Service\Product',
		'Shop\Service\ProductCategory'          => 'Shop\Service\Product\Category',
	    'Shop\Service\ProductGroupPrice'        => 'Shop\Service\Product\GroupPrice',
	    'Shop\Service\ProductImage'             => 'Shop\Service\Product\Image',
	    'Shop\Service\ProductOption'			=> 'Shop\Service\Product\Option',
	    'Shop\Service\ProductSize'			    => 'Shop\Service\Product\Size',
	    'Shop\Service\StockStatus'              => 'Shop\Service\Stock\Status',
	    'Shop\Service\Tax'                      => 'Shop\Service\Tax',
	    'Shop\Service\TaxCode'                  => 'Shop\Service\Tax\Code',
	    'Shop\Service\TaxRate'                  => 'Shop\Service\Tax\Rate',
	),
    'factories' => array(
    	'Shop\Form\Country'						=> 'Shop\Service\Factory\CountryFormFactory',
        'Shop\Form\CustomerAddress'				=> 'Shop\Service\Factory\AddressFormFactory',
    	'Shop\Form\PostZone'					=> 'Shop\Service\Factory\PostZoneFormFactory',
    	'Shop\Form\Product'						=> 'Shop\Service\Factory\ProductFormFactory',
    	'Shop\Form\ProductCategory'				=> 'Shop\Service\Factory\ProductCategoryFormFactory',
        
        'Shop\Options\Checkout'                 => 'Shop\Service\Factory\CheckoutOptionsFactory',
        'Shop\Options\Paypal'                   => 'Shop\Service\Factory\PaypalOptionsFactory',
        'Shop\Options\Shop'                     => 'Shop\Service\Factory\ShopOptionsFactory',
        
        'Shop\Service\Cart'                     => 'Shop\Service\Factory\CartFactory',
        'Shop\Service\Shipping'                 => 'Shop\Service\Factory\ShippingFactory',
    ),
);