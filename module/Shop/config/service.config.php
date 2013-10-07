<?php

return array(
    'shared'        => array(
    	'Shop\Model\CartItem'					=> false,
    ),
	'invokables'    => array(
	
	    'Shop\InputFilter\Product' 			    => 'Shop\InputFilter\Product',
		'Shop\InputFilter\ProductCategory'	    => 'Shop\InputFilter\ProductCategory',
		'Shop\InputFilter\ProductImage'		    => 'Shop\InputFilter\ProductImage',
		'Shop\InputFilter\ProductOption'	    => 'Shop\InputFilter\ProductOption',
		'Shop\InputFilter\ProductPostUnit'	    => 'Shop\InputFilter\ProductPostUnit',
		'Shop\InputFilter\ProductSize'		    => 'Shop\InputFilter\ProductSize',
		'Shop\InputFilter\ProductStockStatus'   => 'Shop\InputFilter\ProductStockStatus',
		'Shop\InputFilter\TaxCode'				=> 'Shop\InputFilter\TaxCode',
		'Shop\InputFilter\TaxRate'				=> 'Shop\InputFilter\TaxRate',
	    
		'Shop\Mapper\Product' 				    => 'Shop\Mapper\Product',
		'Shop\Mapper\ProductCategory'		    => 'Shop\Mapper\ProductCategory',
		'Shop\Mapper\ProductImage'			    => 'Shop\Mapper\ProductImage',
		'Shop\Mapper\ProductOption'			    => 'Shop\Mapper\ProductOption',
		'Shop\Mapper\ProductPostUnit'		    => 'Shop\Mapper\ProductPostUnit',
		'Shop\Mapper\ProductSize'			    => 'Shop\Mapper\ProductSize',
		'Shop\Mapper\ProductStockStatus'	    => 'Shop\Mapper\ProductStockStatus',
		'Shop\Mapper\TaxCode'				    => 'Shop\Mapper\TaxCode',
		'Shop\Mapper\TaxRate'				    => 'Shop\Mapper\TaxRate',
		
		'Shop\Model\Cart'					    => 'Shop\Model\Cart',
		'Shop\Model\CartItem'					=> 'Shop\Model\CartItem',
		
		'Shop\Service\Product' 				    => 'Shop\Service\Product',
		'Shop\Service\ProductCategory'          => 'Shop\Service\ProductCategory',
		'Shop\Service\Shipping'                 => 'Shop\Service\Shipping',
		'Shop\Service\Taxation'                 => 'Shop\Service\Taxation',
	),
);