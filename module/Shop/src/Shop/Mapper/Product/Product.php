<?php
namespace Shop\Mapper\Product;

use UthandoCommon\Mapper\AbstractDbMapper;
use Zend\Db\Sql\Select;

class Product extends AbstractDbMapper
{       
	protected $table = 'product';
	protected $primary = 'productId';
	protected $fetchEnabled = true;
	protected $fetchDisabled = false;

    /**
     * @param $ident
     * @return \Shop\Model\Product\Product|\UthandoCommon\Model\Model
     */
	public function getProductByIdent($ident)
	{
		$ident = (string) $ident;
		$select = $this->getSelect()->where(['ident' => $ident]);
		$resultSet = $this->fetchResult($select);
		$row = $resultSet->current();
		return $row;
	}
	
	public function getFullProductById($id)
	{
	    $select = $this->getSelect();
	    $select->where->equalTo('product.productId', $id);

        $select = $this->setFilter($select);
	    
	    $resultSet = $this->fetchResult($select);
	    $row = $resultSet->current();
	    return $row;
	}
	
	public function getProductsByCategory(array $categoryId, $order=null)
	{
	    $select = $this->getSelect();
	    $select->join(
	        'productCategory',
	        'product.productCategoryId=productCategory.productCategoryId',
	        array(),
	        Select::JOIN_LEFT
	    );
	    
		$select->where->in('product.productCategoryId', $categoryId);

        $select = $this->setFilter($select);
		
		if ($order) {
		    $select = $this->setSortOrder($select, $order);
		}
		
		return $this->fetchResult($select);
	}
	
	public function search(array $search, $sort, $select = null)
	{
	    $productCategoryId = null;
	    
	    if (isset($search[1])) {
	        $productCategoryId = $search[1]['searchString'];
	        unset($search[1]);
	    }
	    
	    if (isset($search[2])) {
	        $discontinued = $search[2]['searchString'];;
	        unset($search[2]);
	    }
	    
        if (isset($search[3])) {
	        $disabled = $search[3]['searchString'];
	        unset($search[3]);
	    }
	    
		$select = $this->getSelect();
		$select->join(
            'productCategory',
            'product.productCategoryId=productCategory.productCategoryId',
            array(),
            Select::JOIN_LEFT
        )
        ->join(
            'productGroup',
            'product.productGroupId=productGroup.productGroupId',
            array(),
            Select::JOIN_LEFT
        )
        ->join(
            'productSize',
            'product.productSizeId=productSize.productSizeId',
            array(),
            Select::JOIN_LEFT
        );
		
		if ($productCategoryId) {
		    $select->where->in('product.productCategoryId', $productCategoryId);
		}
		
		if ($disabled) {
		    $select->where(['product.enabled' => 0]);
		}
		
		if ($discontinued) {
		    $select->where(['product.discontinued' => 1]);
		}
		
		$select = $this->setFilter($select);
		
		return parent::search($search, $sort, $select);
	}
	
	public function searchProducts(array $search)
	{
        $select = $this->getSelect();
        $select->join(
                'productCategory',
                'product.productCategoryId=productCategory.productCategoryId',
                array(),
                Select::JOIN_LEFT
            )->join(
                'postUnit',
                'product.postUnitId=postUnit.postUnitId',
                array(),
                Select::JOIN_LEFT
            )->join(
                'productSize',
                'product.productSizeId=productSize.productSizeId',
                array(),
                Select::JOIN_LEFT
            );

        $select = $this->setFilter($select);
        
        $sort = (isset($search['sort'])) ? $search['sort'] : ''; 
	   
	   return parent::search($search, $sort, $select);
	}

    public function setFilter(Select $select)
    {
        if ($this->getFetchEnabled()) {
            $select->where->equalTo('product.enabled', 1);
        }

        if (!$this->getFetchDisabled()) {
            $select->where->equalTo('product.discontinued', 0);
        }

        return $select;
    }
	
	public function getFetchEnabled()
	{
		return $this->fetchEnabled;
	}

	public function setFetchEnabled($fetchEnabled)
	{
		$this->fetchEnabled = $fetchEnabled;
		return $this;
	}
	
	public function getFetchDisabled()
	{
		return $this->fetchDisabled;
	}

	public function setFetchDisabled($fetchDisabled)
	{
		$this->fetchDisabled = $fetchDisabled;
		return $this;
	}
}
