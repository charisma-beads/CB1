<?php
return array(
	'userAcl' => array(
		'userRoles' => array(
			'guest'	=>array(
				'privileges' => array(
					array('controller' => 'Shop\Controller\Cart', 'action' => 'all'),
					array('controller' => 'Shop\Controller\Catalog', 'action' => 'all'),
					array('controller' => 'Shop\Controller\Checkout', 'action' => array('index')),
					array('controller' => 'Shop\Controller\Product', 'action' => array('view')),
					array('controller' => 'Shop\Controller\Shop', 'action' => array('shop-front')),
				),
			),
			'registered' => array(
				'privileges' => array(
					array('controller' => 'Shop\Controller\Cart', 'action' => 'all'),
					array('controller' => 'Shop\Controller\Catalog', 'action' => 'all'),
					array('controller' => 'Shop\Controller\Checkout', 'action' => 'all'),
					array('controller' => 'Shop\Controller\Product', 'action' => array('view')),
					array('controller' => 'Shop\Controller\Shop', 'action' => array('shop-front')),
				),
			),
			'admin' => array(
				'privileges' => array(
					array('controller' => 'Shop\Controller\Product', 'action' => 'all'),
					array('controller' => 'Shop\Controller\Shop', 'action' => 'all'),
				),
			),
		),
		'userResources' => array(
			'Shop\Controller\Cart',
			'Shop\Controller\Catalog',
			'Shop\Controller\Checkout',
			'Shop\Controller\Product',
			'Shop\Controller\Shop',
		),
	),
    'router' => array(
        'routes' => array(
            'shop' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/shop',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Shop\Controller',
                        'controller'    => 'Shop',
                        'action'        => 'shop-front',
                        'force-ssl'     => 'http'
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'catalog' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/[:categoryIdent]',
                            'constraints' => array(
                            	'categoryIdent' => '[a-zA-Z0-9][a-zA-Z0-9_-]*'
                            ),
                            'defaults' => array(
                            	'controller'    => 'Catalog',
                            	'action'        => 'index',
                            	'categoryIdent' => '',
                                'force-ssl'     => 'http'
                            ),
                        ),
                    	'may_terminate' => true,
                    	'child_routes' => array(
                    		'product' => array(
                    			'type' => 'Segment',
                    			'options' => array(
                    				'route' => '/[:productIdent]',
                    				'constraints' => array(
                    					'productIdent' => '[a-zA-Z0-9][a-zA-Z0-9_-]*'
                    				),
                    				'defaults' => array(
										'action'        => 'view',
                    					'productIdent'  => '',
                    				    'force-ssl'     => 'http'
									),
                    			),
                    		),
                    		'page' => array(
                    			'type'    => 'Segment',
                    			'options' => array(
                    				'route'         => '/page/[:page]',
                    				'constraints'   => array(
                    					'page' => '\d+'
                    				),
                    				'defaults'      => array(
                    					'page'         => 1,
                    				    'force-ssl'    => 'http'
                    				),
                    			),
                    		),
                    	),
                    ),
                	'cart' => array(
                		'type' => 'Segment',
                		'options' => array(
                			'route' => '/cart/[:action[/[:id]]]',
                			'constraints' => array(
                				'action'	=> '[a-zA-Z0-9][a-zA-Z0-9_-]*',
                				'id'		=> '\d+'
                			),
                			'defaults' => array(
                				'controller' 	=> 'Cart',
                				'action'		=> 'view',
                			    'force-ssl'     => 'http'
                			),
                		),
                	),
                	'checkout' => array(
                		'type' => 'Segment',
                		'options' => array(
                			'route' => '/checkout[/:action]',
                			'constraints' => array(
                				'action'	=> '[a-zA-Z0-9][a-zA-Z0-9_-]*'
                			),
                			'defaults' => array(
                				'controller' 	=> 'Checkout',
                				'action'		=> 'index',
                			    'force-ssl'     => 'ssl'
                			),
                		),
                	),
                ),
            ),
        	'admin' => array(
        		'child_routes' => array(
        			'shop' => array(
        				'type'    => 'Segment',
        				'options' => array(
        					'route'    => '/shop',
        					'defaults' => array(
        						'__NAMESPACE__' => 'Shop\Controller',
        						'controller'    => 'Shop',
        						'action'        => 'index',
        					    'force-ssl'     => 'ssl'
        					),
        				),
        			),
        		),
        	),
        ),
    ),
	'navigation' => array(
		'admin' => array(
			'shop' => array(
				'label' => 'Shop',
				'pages' => array(
					'overview' => array(
						'label' => 'Overview',
						'action' => 'index',
						'route' => 'admin/shop',
						'resource' => 'menu:admin'
					),
				),
				'route' => 'admin/shop',
				'resource' => 'menu:admin'
			),
		),
	),
    'view_manager' => array(
    	'template_map' => array(
    		'cart/summary' => __DIR__ . '/../view/shop/cart/cart-summary.phtml',
    	    'shop/cart'    => __DIR__ . '/../view/shop/cart/cart.phtml',
    	),
        'template_path_stack' => array(
            'Shop' => __DIR__ . '/../view',
        ),
    ),
);
