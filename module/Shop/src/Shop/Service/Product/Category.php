<?php
namespace Shop\Service\Product;

use UthandoCommon\Model\ModelInterface;
use UthandoCommon\Service\AbstractMapperService;
use Shop\ShopException;
use Shop\Model\Product\Category as CategoryModel;
use Zend\Form\Form;

class Category extends AbstractMapperService
{
	protected $mapperClass = 'Shop\Mapper\Product\Category';
	protected $form = 'Shop\Form\Product\Category';
	protected $inputFilter = 'Shop\InputFilter\Product\Category';

    protected $serviceAlias = 'ShopProductCategory';
	
	/**
	 * @var \Shop\Service\Product\Image
	 */
	protected $imageService;
	
	public function fetchAll($topLevelOnly=false)
	{
	    if ($topLevelOnly) {
	    	return $this->getMapper()->getTopLevelCategories();
	    } else {
	    	return $this->getMapper()->getAllCategories();
	    }
	}
	
	public function getCategoriesByParentId($parentId)
	{
		$parentId = (int) $parentId;
	
		return ($parentId != 0) ? $this->getMapper()->getSubCategoriesByParentId($parentId) : $this->fetchAll(true);
	}
	
	public function getCategoryByIdent($ident)
	{
		$ident = (string) $ident;
	
		$cat = $this->getMapper()->getCategoryByIdent($ident);
		
		return $cat;
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
	
	public function getCategoryImages($categoryId, $recursive=false)
	{
		$ids = $this->getCategoryChildrenIds($categoryId, $recursive);
		$images = $this->getImageService()->getMapper()->getImagesByCategoryIds($ids);
		
		return $images;
	}
	
	public function getParentCategories($categoryId)
	{
		return $this->getMapper()->getBreadCrumbs($categoryId);
	}
	
	public function search(array $post)
	{
		foreach ($post as $key => $value) {
			if ($key == 'category') {
				$key = preg_replace('(\w+)', 'child.$0', $key);
				$post[$key] = $value;
				unset($post['category']);
			}
		}
		
		return parent::search($post);
	}
	
	public function add(array $post, Form $form = null)
	{
		if (!$post['ident']) {
			$post['ident'] = $post['category'];
		}
		
		$model = $this->getMapper()->getModel();
        $options = [
            'categoryId' => $model->getProductCategoryId(),
        ];

		$form  = $this->getForm($model, $post, $options, true, true);
	
		if (!$form->isValid()) {
			return $form;
		}
	
		$data = $this->getMapper()->extract($form->getData());
		$position = (int) $post['parent'];
		$insertType = (string) $post['categoryInsertType'];
	
		return $this->getMapper()->insertRow($data, $position, $insertType);
	}

    /**
     * @param ModelInterface $model
     * @param array $post
     * @param Form $form
     * @return int|Form
     * @throws ShopException
     * @throws \UthandoCommon\Service\ServiceException
     */
	public function edit(ModelInterface $model, array $post, Form $form = null)
	{
		if (!$model instanceof CategoryModel) {
			throw new ShopException('$model must be an instance of Shop\Model\Product\Category, ' . get_class($model) . ' given.');
		}
		
		if (!$post['ident']) {
			$post['ident'] = $post['category'];
		}
		
		$model->setDateModified();
        $options = [
            'categoryId' => $model->getProductCategoryId(),
        ];
		
		$form = $this->getForm($model, $post, $options, true, true);
	
		if (!$form->isValid()) {
			return $form;
		}
		
		$category = $this->getById($model->getProductCategoryId());
	
		if ($category) {
			// if category position has changed then we need to delete it
			// and reinsert it in the new position else just update it.
			// no this should update, no reinserting and deleting thank you.
			// move this to the mapper class.
			if ('noInsert' !== $post['categoryInsertType']) {
				// TODO find children and move them as well.
				return $form;
			} else {
				$result = $this->save($model);
			}
		} else {
			throw new ShopException('Product Category id does not exist');
		}
	
		return $result;
	}
	
	public function toggleEnabled(CategoryModel $category)
	{	
		//check for parent and if it's enabled or not, if disabled don't update.
		$parents = $this->getParentCategories(
            $category->getProductCategoryId()
        )->toArray();
		
		array_pop($parents);
		$parent = array_slice($parents, -1, 1);
		
		if (count($parent) && !$parent[0]['enabled']) {
			throw new ShopException("Can't change enabled status on child while parent is disabled. First enable the parent category");
		}

		if (true === $category->getEnabled()) {
			$category->setEnabled(false);
		} else {
			$category->setEnabled(true);
		}
		
		$category->setDateModified();
		
		return $this->getMapper()->toggleEnabled($category);
	}
	
	/**
	 * @return \Shop\Service\Product\Image
	 */
	public function getImageService()
	{
		if (!$this->imageService) {
			$sl = $this->getServiceLocator();
			$this->imageService = $sl->get('Shop\Service\Product\Image');
		}
	
		return $this->imageService;
	}
}
