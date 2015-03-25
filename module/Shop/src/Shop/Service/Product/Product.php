<?php
namespace Shop\Service\Product;

use Shop\Model\Product\Product as ProductModel;
use UthandoCommon\Service\AbstractRelationalMapperService;
use Zend\EventManager\Event;

class Product extends AbstractRelationalMapperService
{
    protected $serviceAlias = 'ShopProduct';
    
    protected $tags = [
        'product', 'image', 'category',
        'group', 'size', 'tax-code',
        'post-unit', 'option',
    ];

    protected $referenceMap = [
        'productCategory'   => [
            'refCol'    => 'productCategoryId',
            'service'   => 'ShopProductCategory',
        ],
        'productSize'       => [
            'refCol'    => 'productSizeId',
            'service'   => 'ShopProductSize',
        ],
        'taxCode'           => [
            'refCol'    => 'taxCodeId',
            'service'   => 'ShopTaxCode',
        ],
        'postUnit'          => [
            'refCol'    => 'postUnitId',
            'service'   => 'ShopPostUnit',
        ],
        'productGroup'      => [
            'refCol'    => 'productGroupId',
            'service'   => 'ShopProductGroup',
        ],
        'productImage'      => [
            'refCol'    => 'productId',
            'service'   => 'ShopProductImage',
            'getMethod' => 'getImagesByProductId',
        ],
        'productOption' => [
            'refCol'    => 'productId',
            'service'   => 'ShopProductOption',
            'getMethod' => 'getOptionsByProductId',
        ],
    ];
    
    /**
     * Attach events
     */
    public function attachEvents()
    {
        $this->getEventManager()->attach([
            'pre.form'
        ], [$this, 'setProductIdent']);
    }

    /**
     * @param int $id
     * @return array|mixed|\UthandoCommon\Model\ModelInterface|ProductModel
     */
	public function getFullProductById($id)
	{
	    $id = (int) $id;
	    $product = $this->getById($id);
	    
	    $this->populate($product, true);
	    
	    return $product;
	    
	}

    /**
     * @param string $ident
     * @return \Shop\Model\Product\Product
     */
	public function getProductByIdent($ident)
	{
        /* @var $mapper \Shop\Mapper\Product\Product */
        $mapper = $this->getMapper();
		$product = $mapper->getProductByIdent($ident);
        $this->populate($product, true);
        return $product;
	}

    /**
     * @param string|int $category
     * @param null $order
     * @param bool $deep
     * @return \Zend\Db\ResultSet\HydratingResultSet|\Zend\Db\ResultSet\ResultSet|\Zend\Paginator\Paginator
     * @throws \UthandoCommon\Service\ServiceException
     */
	public function getProductsByCategory($category, $order=null, $deep=true)
	{
        /* @var $mapper \Shop\Mapper\Product\Product */
        $mapper = $this->getMapper();

        /* @var $categoryService \Shop\Service\Product\Category */
        $categoryService = $this->getRelatedService('productCategory');

		if (is_string($category)) {
            /* @var $cat \Shop\Model\Product\Category */
            $cat = $categoryService->getCategoryByIdent($category);
			$categoryId = (null === $cat) ? 0 : $cat->getProductCategoryId();
		} else {
			$categoryId = (int) $category;
		}
	
		if (true === $deep) {
			$ids = $categoryService->getCategoryChildrenIds(
				$categoryId, true
			);
	
			$ids[] = $categoryId;
			$categoryId = (null === $ids) ? $categoryId : $ids;
		}
		
		$products = $mapper->getProductsByCategory($categoryId, $order);

        foreach ($products as $product) {
		     $this->populate($product, true);
		}
	
		return $products;
	}
	
	public function search(array $post)
	{
	    if (isset($post['productCategoryId']) && $post['productCategoryId'] != '') {
	        /* @var $categoryService \Shop\Service\Product\Category */
	        $categoryService = $this->getRelatedService('productCategory');
	        
	        $ids = $categoryService->getCategoryChildrenIds(
	            $post['productCategoryId'], true
	        );
	        
	        if (!empty($ids)) {
	            $ids[] = $post['productCategoryId'];
	        }
	        
	        $categoryId = (empty($ids)) ? (array) $post['productCategoryId'] : $ids;
	        
	        $post['productCategoryId'] = $categoryId;
	    }
	    
	    return parent::search($post);
	}

    /**
     * @param array $search
     * @return \Zend\Db\ResultSet\HydratingResultSet|\Zend\Db\ResultSet\ResultSet|\Zend\Paginator\Paginator
     */
	public function searchProducts(array $search)
	{
	    $search = array(array(
            'searchString'  => $search['productSearch'],
            'columns'       => [
               'sku', 'name', 'shortDescription', 'productCategory.category'
            ],
        ));

        /* @var $mapper \Shop\Mapper\Product\Product */
        $mapper = $this->getMapper();
	    
	    $products = $mapper->searchProducts($search);

	    foreach ($products as $product) {
	        $this->populate($product, true);
	    }
	    
	    return $products;
	}

    /**
     * Make a new product based on a product.
     *
     * @param int $id
     * @return ProductModel $product
     */
    public function makeDuplicate($id)
    {
        $product = $this->getFullProductById($id);

        $product->setProductId(null)
            ->setSku(null)
            ->setName(null)
            ->setIdent(null);

        return $product;
    }

    /**
     * @param ProductModel $product
     * @return int
     */
	public function toggleEnabled(ProductModel $product)
	{
		$enabled = (true === $product->getEnabled()) ? 0 : 1;
		
		$form  = $this->getForm($product, ['enabled' => $enabled], true, true);
		$form->setValidationGroup('enabled');
	
		return parent::edit($product, [], $form);
	}
	
	public function setProductIdent(Event $e)
	{
	    $data = $e->getParam('data');
	
	    if (null === $data) {
	        return;
	    }
	    
	    if ($data instanceof ProductModel) {
	        $data->setIdent($data->getSku() . ' ' . $data->getName());
	    } elseif (is_array($data)) {
	        $data['ident'] = $data['sku'] . ' ' . $data['name'];
	    }
	
	    $e->setParam('data', $data);
	}
}