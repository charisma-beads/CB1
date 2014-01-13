<?php
namespace Shop\Service\Product;

use Application\Service\AbstractService;
use Shop\ShopException;
use Shop\Model\Product\Category as CategoryModel;

class Category extends AbstractService
{
	protected $mapperClass = 'Shop\Mapper\ProductCategory';
	protected $form = 'Shop\Form\ProductCategory';
	protected $inputFilter = 'Shop\InputFilter\ProductCategory';
	
	public function fetchAll($topLevelOnly=false)
	{
	    if ($topLevelOnly) {
	    	return $this->getMapper()->getTopLevelCategories();
	    } else {
	    	return $this->getMapper()->getAllCategories();
	    }
	}
	
	public function searchCategories(array $post)
	{
	    $category = (isset($post['category'])) ? (string) $post['category'] : '';
	    $sort = (isset($post['sort'])) ? (string) $post['sort'] : '';
	    
		return $this->getMapper()->searchCategories($category, $sort);
	}
	
	public function getCategoriesByParentId($parentId)
	{
		$parentId = (int) $parentId;
	
		return ($parentId != 0) ? $this->getMapper()->getSubCategoriesByParentId($parentId) : $this->fetchAll(true);
	}
	
	public function getCategoryByIdent($ident)
	{
		$ident = (string) $ident;
	
		return $this->getMapper()->getCategoryByIdent($ident);
	}
	
	public function getCategoryChildrenIds($categoryId, $recursive=false)
	{
		$categories = $this->getMapper()
			->getSubCategoriesByParentId($categoryId, $recursive);
		
		$cats = array();
	
		foreach ($categories as $category) {
			$cats[] = $category->getProductCategoryId();
		}
	
		return $cats;
	}
	
	public function getParentCategories($categoryId)
	{
		return $this->getMapper()->getBreadCrumbs($categoryId);
	}
	
	public function addCategory($post)
	{
		$page = $this->getMapper()->getModel();
		$form  = $this->getForm($page, $post);
		$position = (int) $post['parent'];
		$insertType = (string) $post['categoryInsertType'];
	
		if (!$form->isValid()) {
			return $form;
		}
	
		$data = $this->getMapper()->extract($form->getData());
	
		return $this->getMapper()->insertRow($data, $position, $insertType);
	}
	
	public function editCategory(CategoryModel $category, $post)
	{
		$category->setDateModified();
		
		$form  = $this->getForm($category, $post);
	
		if (!$form->isValid()) {
			return $form;
		}
		
		$category = $this->getById($category->getProductCategoryId());
	
		if ($category) {
			// if page postion has changed then we need to delete it
			// and reinsert it in the new position else just update it.
			if ('noInsert' !== $post['categoryInsertType']) {
				// TODO find children and move them as well.
				return $form;
			} else {
				$data = $this->getMapper()->extract($form->getData());
				$result = $this->getMapper()->update($category->getProductCategoryId(), $data);
			}
		} else {
			throw new ShopException('Product Category id does not exist');
		}
	
		return $result;
	}
	
	public function toggleEnabled(CategoryModel $category)
	{
		if (true === $category->getEnabled()) {
			$category->setEnabled(false);
		} else {
			$category->setEnabled(true);
		}
		
		//check for parent and if it's enabled or not
		// if disabled don't update.
		$parents = $this->getParentCategories($category->getProductCategoryId());
		
		if ($parents->count() > 1) {
			$parent = $parents->current();
			
			if ((false === $parent->getEnabled() && false === $category->getEnabled()) || 
				(false === $parent->getEnabled() && true === $category->getEnabled())) {
				throw new ShopException("Can't change enabled status on child while parent is disabled. First enable the parent category");
			}
		}
		
		$category->setDateModified();
		
		return $this->getMapper()->toggleEnabled($category);
	}
}
