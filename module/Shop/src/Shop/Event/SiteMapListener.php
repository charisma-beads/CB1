<?php
/**
 * Uthando CMS (http://www.shaunfreeman.co.uk/)
 *
 * @package   Shop\Event
 * @author    Shaun Freeman <shaun@shaunfreeman.co.uk>
 * @copyright Copyright (c) 2015 Shaun Freeman. (http://www.shaunfreeman.co.uk)
 * @license   see LICENSE
 */

namespace Shop\Event;

use Shop\Service\ProductCategoryService;
use Shop\Service\ProductService;
use Navigation\Service\MenuService;
use Navigation\Service\SiteMapService;
use Zend\EventManager\Event;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\EventManager\ListenerAggregateTrait;

/**
 * Class SiteMapListener
 *
 * @package Shop\Event
 */
class SiteMapListener implements ListenerAggregateInterface
{
    use ListenerAggregateTrait;

    /**
     * @param EventManagerInterface $events
     */
    public function attach(EventManagerInterface $events)
    {
        $events = $events->getSharedManager();

        $this->listeners[] = $events->attach([
            SiteMapService::class,
        ], ['uthando.site-map'], [$this, 'addShopPages']);
    }

    /**
     * @param Event $e
     */
    public function addShopPages(Event $e)
    {
        /* @var \Zend\Navigation\Navigation $navigation */
        $navigation = $e->getParam('navigation');

        /* @var \Shop\Service\ProductService $productService */
        $productService = $e->getTarget()->getService(ProductService::class);
        /* @var \Shop\Service\ProductCategoryService $categoryService */
        $categoryService = $e->getTarget()->getService(ProductCategoryService::class);
        /* @var \Navigation\Service\MenuService $service */
        $menuService = $e->getTarget()->getService(MenuService::class);

        $cats = $categoryService->fetchAll();
        $pages = [];
        /* @var \Shop\Model\ProductCategoryModel $category */
        foreach ($cats as $category) {
            $pages[$category->getIdent()] = [
                'label'     => $category->getCategory(),
                'route'     => 'shop/catalog',
                'params'    => [
                    'categoryIdent' => $category->getIdent(),
                ],
            ];

            // optimise this for only an array
            $products = $productService->setPopulate(false)
                ->getProductsByCategory((int) $category->getProductCategoryId(), null, false);

            /* @var \Shop\Model\Product\ProductModel $product */
            foreach ($products as $product) {
                $pages[$category->getIdent()]['pages'][$product->getIdent()] = [
                    'label'     => $category->getCategory(),
                    'route'     => 'shop/catalog/product',
                    'params'    => [
                        'categoryIdent' => $category->getIdent(),
                        'productIdent' => $product->getIdent(),
                    ],
                ];
            }
        }

        // find shop page
        $shopPage = $navigation->findOneByRoute('shop');

        // add categories to shop page
        $shopPage->addPages($menuService->preparePages($pages));
    }
}
