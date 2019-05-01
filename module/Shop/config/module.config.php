<?php
return [
    'asset_manager' => [
        'resolver_configs' => [
            'collections' => [
                'css/uthando-admin.css' => [
                    'css/shop.css',
                    'css/typeaheadjs.css',
                ],
            ],
            'paths' => [
                'Shop' => __DIR__ . '/../public',
            ],
        ],
    ],
    'controllers' => [
        'invokables' => [
            \Shop\Controller\AdvertController::class            => \Shop\Controller\AdvertController::class,
            \Shop\Controller\CartController::class              => \Shop\Controller\CartController::class,
            \Shop\Controller\CatalogController::class           => \Shop\Controller\CatalogController::class,
            \Shop\Controller\CheckoutController::class          => \Shop\Controller\CheckoutController::class,
            \Shop\Controller\ConsoleController::class           => \Shop\Controller\ConsoleController::class,
            \Shop\Controller\CountryController::class           => \Shop\Controller\CountryController::class,
            \Shop\Controller\CountryProvinceController::class   => \Shop\Controller\CountryProvinceController::class,
            \Shop\Controller\CreateOrderController::class       => \Shop\Controller\CreateOrderController::class,
            \Shop\Controller\CustomerController::class          => \Shop\Controller\CustomerController::class,
            \Shop\Controller\CustomerAddressController::class   => \Shop\Controller\CustomerAddressController::class,
            \Shop\Controller\FaqController::class               => \Shop\Controller\FaqController::class,
            \Shop\Controller\OrderController::class             => \Shop\Controller\OrderController::class,
            \Shop\Controller\PaymentController::class           => \Shop\Controller\PaymentController::class,
            \Shop\Controller\PaypalController::class            => \Shop\Controller\PaypalController::class,
            \Shop\Controller\PostCostController::class          => \Shop\Controller\PostCostController::class,
            \Shop\Controller\PostLevelController::class         => \Shop\Controller\PostLevelController::class,
            \Shop\Controller\PostUnitController::class          => \Shop\Controller\PostUnitController::class,
            \Shop\Controller\PostZoneController::class          => \Shop\Controller\PostZoneController::class,
            \Shop\Controller\ProductCategoryController::class   => \Shop\Controller\ProductCategoryController::class,
            \Shop\Controller\ProductController::class           => \Shop\Controller\ProductController::class,
            \Shop\Controller\ProductGroupController::class      => \Shop\Controller\ProductGroupController::class,
            \Shop\Controller\ProductImageController::class      => \Shop\Controller\ProductImageController::class,
            \Shop\Controller\ProductOptionController::class     => \Shop\Controller\ProductOptionController::class,
            \Shop\Controller\ProductSizeController::class       => \Shop\Controller\ProductSizeController::class,
            \Shop\Controller\ReportController::class            => \Shop\Controller\ReportController::class,
            \Shop\Controller\SettingsController::class          => \Shop\Controller\SettingsController::class,
            \Shop\Controller\ShopController::class              => \Shop\Controller\ShopController::class,
            \Shop\Controller\TaxCodeController::class           => \Shop\Controller\TaxCodeController::class,
            \Shop\Controller\TaxRateController::class           => \Shop\Controller\TaxRateController::class,
            \Shop\Controller\VoucherAdminController::class      => \Shop\Controller\VoucherAdminController::class,
            \Shop\Controller\VoucherCodesController::class      => \Shop\Controller\VoucherCodesController::class,
        ],
    ],
    'controller_plugins' => [
        'aliases' => [
            'Order' => \Shop\Controller\Plugin\Order::class,
        ],
        'invokables' => [
            \Shop\Controller\Plugin\Order::class => \Shop\Controller\Plugin\Order::class,
        ],
    ],
    'service_manager' => [
        'aliases'   => [
            'ExceptionMailer\ErrorHandling' => Shop\Exception\Mailer::class
        ],
        'invokables' => [
            \Shop\Service\ReportService::class => \Shop\Service\ReportService::class,
        ],
        'factories' => [
            Shop\Exception\Mailer::class                    => Shop\Exception\MailerFactory::class,

            \Shop\Options\CartCookieOptions::class          => \Shop\Service\Factory\CartCookieOptionsFactory::class,
            \Shop\Options\CartOptions::class                => \Shop\Service\Factory\CartOptionsFactory::class,
            \Shop\Options\InvoiceOptions::class             => \Shop\Service\Factory\InvoiceOptionsFactory::class,
            \Shop\Options\OrderOptions::class               => \Shop\Service\Factory\OrderOptionsFactory::class,
            \Shop\Options\PaypalOptions::class              => \Shop\Service\Factory\PaypalOptionsFactory::class,
            \Shop\Options\ReportsOptions::class             => \Shop\Service\Factory\ReportsOptionsFactory::class,
            \Shop\Options\ShopOptions::class                => \Shop\Service\Factory\ShopOptionsFactory::class,
            \Shop\Options\NewProductsCarouselOptions::class => \Shop\Service\Factory\NewProductsCarouselOptionsFactory::class,

            \Shop\Service\CartCookieService::class          => \Shop\Service\Factory\CartCookieFactory::class,
            \Shop\Service\ShippingService::class            => Shop\Service\Factory\ShippingServiceFactory::class,
            \Shop\Service\TaxService::class                 => Shop\Service\Factory\TaxServiceFactory::class,
        ],
        'shared' => [
            \Shop\Service\TaxService::class => false // will not be shared
        ],
    ],
    'uthando_services' => [
        'invokables' => [
            \Shop\Service\AdvertHitService::class           => \Shop\Service\AdvertHitService::class,
            \Shop\Service\AdvertService::class              => \Shop\Service\AdvertService::class,
            \Shop\Service\CartItemService::class            => \Shop\Service\CartItemService::class,
            \Shop\Service\CartService::class                => \Shop\Service\CartService::class,
            \Shop\Service\CountryProvinceService::class     => \Shop\Service\CountryProvinceService::class,
            \Shop\Service\CountryService::class             => \Shop\Service\CountryService::class,
            \Shop\Service\CreditCardService::class          => \Shop\Service\CreditCardService::class,
            \Shop\Service\CustomerAddressService::class     => \Shop\Service\CustomerAddressService::class,
            \Shop\Service\CustomerPrefixService::class      => \Shop\Service\CustomerPrefixService::class,
            \Shop\Service\CustomerService::class            => \Shop\Service\CustomerService::class,
            \Shop\Service\FaqService::class                 => \Shop\Service\FaqService::class,
            \Shop\Service\OrderLineService::class           => \Shop\Service\OrderLineService::class,
            \Shop\Service\OrderService::class               => \Shop\Service\OrderService::class,
            \Shop\Service\OrderStatusService::class         => \Shop\Service\OrderStatusService::class,
            \Shop\Service\PaypalService::class              => \Shop\Service\PaypalService::class,
            \Shop\Service\PostCostService::class            => \Shop\Service\PostCostService::class,
            \Shop\Service\PostLevelService::class           => \Shop\Service\PostLevelService::class,
            \Shop\Service\PostUnitService::class            => \Shop\Service\PostUnitService::class,
            \Shop\Service\PostZoneService::class            => \Shop\Service\PostZoneService::class,
            \Shop\Service\ProductCategoryService::class     => \Shop\Service\ProductCategoryService::class,
            \Shop\Service\ProductGroupService::class        => \Shop\Service\ProductGroupService::class,
            \Shop\Service\ProductImageService::class        => \Shop\Service\ProductImageService::class,
            \Shop\Service\ProductOptionService::class       => \Shop\Service\ProductOptionService::class,
            \Shop\Service\ProductService::class             => \Shop\Service\ProductService::class,
            \Shop\Service\ProductSizeService::class         => \Shop\Service\ProductSizeService::class,
            \Shop\Service\TaxCodeService::class             => \Shop\Service\TaxCodeService::class,
            \Shop\Service\TaxRateService::class             => \Shop\Service\TaxRateService::class,
            \Shop\Service\VoucherCodeService::class         => \Shop\Service\VoucherCodeService::class,
            \Shop\Service\VoucherCustomerMapService::class  => \Shop\Service\VoucherCustomerMapService::class,
        ],
    ],
    'view_helpers' => [
        'aliases' => [
            'ShopAlert'                 => \Shop\View\Alert::class,
            'BreadCrumbs'               => \Shop\View\Breadcrumb::class,
            'CartOption'                => \Shop\View\CartOption::class,
            'Cart'                      => \Shop\View\Cart::class,
            'Category'                  => \Shop\View\Category::class,
            'CountrySelect'             => \Shop\View\CountrySelect::class,
            'CustomerAddress'           => \Shop\View\CustomerAddress::class,
            'MonthYearSelect'           => \Shop\View\FormMonthSelect::class,
            'InvoiceOption'             => \Shop\View\InvoiceOption::class,
            'NormaliseOrderNumber'      => \Shop\View\NormaliseOrderNumber::class,
            'NewProductsCarouselOption' => \Shop\View\NewProductsCarouselOption::class,
            'OrderStatus'               => \Shop\View\OrderStatus::class,
            'PercentFormat'             => \Shop\View\PercentFormat::class,
            'PriceFormat'               => \Shop\View\PriceFormat::class,
            'ProductHelper'             => \Shop\View\Product::class,
            'ProductCategoryImage'      => \Shop\View\ProductCategoryImage::class,
            'ProductImage'              => \Shop\View\ProductImage::class,
            'ProductOptions'            => \Shop\View\ProductOptions::class,
            'ProductPrice'              => \Shop\View\ProductPrice::class,
            'ProductSearch'             => \Shop\View\ProductSearch::class,
            'ProductTableRowState'      => \Shop\View\ProductTableRowState::class,
            'ShopOption'                => \Shop\View\ShopOption::class,
            'StructuredData'            => \Shop\View\StructuredData::class,
        ],
        'invokables'    => [
            \Shop\View\Alert::class                     => \Shop\View\Alert::class,
            \Shop\View\Breadcrumb::class                => \Shop\View\Breadcrumb::class,
            \Shop\View\CartOption::class                => \Shop\View\CartOption::class,
            \Shop\View\Cart::class                      => \Shop\View\Cart::class,
            \Shop\View\Category::class                  => \Shop\View\Category::class,
            \Shop\View\CountrySelect::class             => \Shop\View\CountrySelect::class,
            \Shop\View\CustomerAddress::class           => \Shop\View\CustomerAddress::class,
            \Shop\View\FormMonthSelect::class           => \Shop\View\FormMonthSelect::class,
            \Shop\View\InvoiceOption::class             => \Shop\View\InvoiceOption::class,
            \Shop\View\NormaliseOrderNumber::class      => \Shop\View\NormaliseOrderNumber::class,
            \Shop\View\NewProductsCarouselOption::class => \Shop\View\NewProductsCarouselOption::class,
            \Shop\View\OrderStatus::class               => \Shop\View\OrderStatus::class,
            \Shop\View\PercentFormat::class             => \Shop\View\PercentFormat::class,
            \Shop\View\PriceFormat::class               => \Shop\View\PriceFormat::class,
            \Shop\View\Product::class                   => \Shop\View\Product::class,
            \Shop\View\ProductCategoryImage::class      => \Shop\View\ProductCategoryImage::class,
            \Shop\View\ProductImage::class              => \Shop\View\ProductImage::class,
            \Shop\View\ProductOptions::class            => \Shop\View\ProductOptions::class,
            \Shop\View\ProductPrice::class              => \Shop\View\ProductPrice::class,
            \Shop\View\ProductSearch::class             => \Shop\View\ProductSearch::class,
            \Shop\View\ProductTableRowState::class      => \Shop\View\ProductTableRowState::class,
            \Shop\View\ShopOption::class                => \Shop\View\ShopOption::class,
            \Shop\View\StructuredData::class            => \Shop\View\StructuredData::class,
        ],
    ],
    'view_manager' => [
        'template_map' => [
            'mail/error'           => __DIR__ . '/../view/error/mail.phtml',
            'cart/summary'         => __DIR__ . '/../view/shop/cart/cart-summary.phtml',
            'shop/cart'            => __DIR__ . '/../view/shop/cart/cart.phtml',
            'shop/order/details'   => __DIR__ . '/../view/shop/order/order-details.phtml',
            'error/mail'           => __DIR__ . '/../view/error/mail.phtml', // Exception_Mailer
            'error/paypal'         => __DIR__ . '/../view/error/paypal-error.phtml', // Exception_Mailer
        ],
        'template_path_stack' => [
            'shop' => __DIR__ . '/../view'
        ],
    ],
];
