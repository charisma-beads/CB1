<?php
/**
 * Uthando CMS (http://www.shaunfreeman.co.uk/)
 *
 * @package   Shop\Controller
 * @author    Shaun Freeman <shaun@shaunfreeman.co.uk>
 * @copyright Copyright (c) 2014 Shaun Freeman. (http://www.shaunfreeman.co.uk)
 * @license   see LICENSE.txt
 */

namespace Shop\Controller;

use Shop\ShopException;
use UthandoCommon\Service\ServiceTrait;
use Zend\Http\PhpEnvironment\Response;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/**
 * Class Catalog
 *
 * @package Shop\Controller
 * @method \Zend\Session\Container sessionContainer()
 */
class Catalog extends AbstractActionController
{
    use ServiceTrait;

    /**
     *
     * @var \Shop\Service\Product\Product
     */
    protected $productService;

    /**
     *
     * @var \Shop\Service\Product\Category
     */
    protected $productCategoryService;

    /**
     *
     * @var \Shop\Options\ShopOptions;
     */
    protected $shopOptions;

    public function indexAction()
    {
        $ident = $this->params()->fromRoute('categoryIdent', 0);
        $page = $this->params()->fromRoute('page', 1);
        $options = $this->getShopOptions();
        
        $category = $this->getProductCategoryService()->getCategoryByIdent($ident);
        
        // make more gracefull with setExceptionMessages trait.
        if (!$category) {
            return $this->redirect()->toRoute('shop');
        }
        
        $products = $this->getProductService()
            ->usePaginator([
                'limit' => $options->getProductsPerPage(),
                'page' => $page
        ])->getProductsByCategory($category->getIdent(), $options->getProductsOrderCol());

        $subCategories = $this->getProductCategoryService()->getCategoriesByParentId($category->getProductCategoryId());
        
        return new ViewModel([
            'bread' => $this->getBreadcrumb($category->getProductCategoryId()),
            'category' => $category,
            'subCategories' => $subCategories,
            'products' => $products
        ]);
    }

    public function viewAction()
    {
        $product = $this->getProductService()->getProductByIdent($this->params('productIdent', 0));

        if (!$product) {
            return $this->redirect()->toRoute('shop');
        }
        
        return new ViewModel([
            'bread' => $this->getBreadcrumb($product->getProductCategoryId()),
            'product' => $product,
        ]);
    }

    public function searchAction()
    {
        $searchData = [];
        $session = $this->sessionContainer('CatalogSearch');
        $prg = null;

        if ($this->getRequest()->isXmlHttpRequest()) {
            $searchData = $this->params()->fromPost();
        } else {
            $prg = $this->prg();
        }

        if ($prg instanceof Response) {
            return $prg;
        } elseif (false === $prg) {
            $searchData = $session->offsetGet('searchData');
        } elseif (is_array($prg)) {
            $searchData = $prg;
        }

        $options = $this->getShopOptions();
        $page = (isset($searchData['page'])) ? $searchData['page'] : $session->offsetGet('page');
        $sort = (isset($searchData['sort'])) ? $searchData['sort'] : $options->getProductsOrderCol();
        $sl = $this->getServiceLocator();
        
        $form = $sl->get('FormElementManager')->get('ShopCatalogSearch');
        $inputFilter = $sl->get('InputFilterManager')->get('ShopCatalogSearch');
        $form->setInputFilter($inputFilter);
        $form->setData($searchData);

        $formData = ($form->isValid()) ? $form->getData() : [];

        $session->offsetSet('searchData', $formData);
        $session->offsetSet('page', $page);

        $this->layout()->setVariable('searchData', $searchData);
        
        $products = $this->getProductService()->usePaginator([
            'limit' => $options->getProductsPerPage(),
            'page' => $page,
        ])->searchProducts($formData, $sort);
        
        $viewModel = new ViewModel([
            'products' => $products,
        ]);

        if ($this->getRequest()->isXmlHttpRequest()) {
            $viewModel->setTerminal(true);
        }
        
        return $viewModel;
    }

    public function getBreadcrumb($category)
    {
        return $this->getProductCategoryService()->getParentCategories($category);
    }

    /**
     *
     * @return \Shop\Options\ShopOptions;
     */
    protected function getShopOptions()
    {
        if (! $this->shopOptions) {
            $sl = $this->getServiceLocator();
            $this->shopOptions = $sl->get('Shop\Options\Shop');
        }
        
        return $this->shopOptions;
    }

    /**
     *
     * @return \Shop\Service\Product\Product
     */
    protected function getProductService()
    {
        return $this->getService('ShopProduct');
    }

    /**
     *
     * @return \Shop\Service\Product\Category
     */
    protected function getProductCategoryService()
    {
        return $this->getService('ShopProductCategory');
    }
}
