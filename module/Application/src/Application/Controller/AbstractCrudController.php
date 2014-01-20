<?php
namespace Application\Controller;

use Exception;
use Application\Controller\AbstractController;
use Zend\Form\Form;
use Zend\View\Model\ViewModel;


abstract class AbstractCrudController extends AbstractController
{   
    const DATABASE_SAVE_ERROR		= 'row %s could not be saved to table %s due to a database error.';
    const DATABASE_ADD_ERROR		= 'record could not be saved to table %s due to a database error.';
    const DATABASE_SAVE_SUCCESS		= 'row %s has been saved to database table %s.';
    const DATABASE_DELETE_ERROR		= 'row %s could not be deleted form table %s due to a database error.';
    const DATABASE_DELETE_SUCCESS	= 'row %s has been deleted from the database table %s.';
    const FORM_ERROR				= 'There were one or more isues with your submission. Please correct them as indicated below.';
    
    protected $searchDefaultParams;
    protected $serviceName;
    protected $service;
    protected $route;
    
    public function indexAction()
    {
    	$page = $this->params()->fromRoute('page', 1);
    		
    	return new ViewModel(array(
    		'models' => $this->getService()->usePaginator(array(
    			'limit'	=> 25,
    			'page'	=> $page
    		))->search($this->getSearchDefaultParams())
    	));
    }
    
    public function listAction()
    {
    	if (!$this->getRequest()->isXmlHttpRequest()) {
    		return $this->redirect()->toRoute($this->getRoute());
    	}
    		
    	$params = $this->params()->fromPost();
    		
    	$viewModel = new ViewModel(array(
    		'models' => $this->getService()->usePaginator(array(
    			'limit'	=> $params['count'],
    			'page'	=> $params['page']
    		))->search($params)
    	));
    		
    	$viewModel->setTerminal(true);
    		
    	return $viewModel;
    }
    
    public function addAction()
    {
    	$request = $this->getRequest();
    
    	if ($request->isPost()) {
    
    		$result = $this->getService()->add($request->getPost());
    
    		if ($result instanceof Form) {
    
    			$this->flashMessenger()->addInfoMessage(self::FORM_ERROR);
    
    			return new ViewModel(array(
    				'form' => $result
    			));
    
    		} else {
    			if ($result) {
    				$tableName = $this->getService()->getMapper()->getTable();
    				$this->flashMessenger()->addSuccessMessage(sprintf(self::DATABASE_SAVE_SUCCESS, $result, $tableName));
    			} else {
    				$this->flashMessenger()->addErrorMessage(sprintf(self::DATABASE_ADD_ERROR, $tableName));
    			}
    
    			return $this->redirect()->toRoute($this->getRoute());
    		}
    	}
    
    	return new ViewModel(array(
    		'form' => $this->getService()->getForm(),
    	));
    }
    
    public function editAction()
    {
    	$id = (int) $this->params('id', 0);
    	if (!$id) {
    		return $this->redirect()->toRoute($this->getRoute(), array(
    			'action' => 'add'
    		));
    	}
    
    	try {
    		$model = $this->getService()->getById($id);
    
	    	$request = $this->getRequest();
	    
	    	if ($request->isPost()) {
	    		
	    		// primary key ids must match.
	    		$pk = $this->getService()->getMapper()->getPrimaryKey();
	    		$tableName = $this->getService()->getMapper()->getTable();
	    		$modelMethod = 'get' . ucwords($pk);
	    		$post = $this->params()->fromPost();
	    		
	    		if ($post[$pk] != $model->$modelMethod()) {
	    			throw new \Exception('Primary keys do not match.');
	    		}
	    
	    		$result = $this->getService()->edit($model, $post);
	    
	    		if ($result instanceof Form) {
	    
	    			$this->flashMessenger()->addInfoMessage(self::FORM_ERROR);
	    
	    			return new ViewModel(array(
	    				'form'	=> $result,
	    				'model'	=> $model,
	    			));
	    		} else {
	    			if ($result) {
	    				$this->flashMessenger()->addSuccessMessage(sprintf(self::DATABASE_SAVE_SUCCESS, $id, $tableName));
	    			} else {
	    				$this->flashMessenger()->addErrorMessage(sprintf(self::DATABASE_SAVE_ERROR, id, $tableName));
	    			}
	    
	    			return $this->redirect()->toRoute($this->getRoute());
	    		}
	    	}
	    	
	    	$form = $this->getService()->getForm($model);
	    	
    	} catch (\Exception $e) {
    		$this->setExceptionMessages($e);
    		return $this->redirect()->toRoute($this->getRoute(), array(
    			'action' => 'list'
    		));
    	}
    
    	return new ViewModel(array(
    		'form'	=> $form,
    		'model'	=> $model,
    	));
    }
    
    public function deleteAction()
    {
    	$request = $this->getRequest();
    
    	$tableName = $this->getService()->getMapper()->getTable();
    	$pk = $this->getService()->getMapper()->getPrimaryKey();
    	$id = (int) $request->getPost($pk);
    
    	if (!$id) {
    		return $this->redirect()->toRoute($this->getRoute());
    	}
    
    	if ($request->isPost()) {
    		$del = $request->getPost('submit', 'No');
    
    		if ($del == 'delete') {
    			try {
    				$result = $this->getService()->delete($id);
    
    				if ($result) {
    					$this->flashMessenger()->addSuccessMessage(sprintf(self::DATABASE_DELETE_SUCCESS, $id, $tableName));
    				} else {
    					$this->flashMessenger()->addErrorMessage(sprintf(self::DATABASE_DELETE_ERROR, $id, $tableName));
    				}
    			} catch (\Exception $e) {
    				$this->setExceptionMessages($e);
    			}
    		}
    	}
    
    	return $this->redirect()->toRoute($this->getRoute());
    }
    
    protected function getServiceName()
    {
    	return $this->serviceName;
    }
    
    /**
     * @param string $service
     * @return \Application\Service\AbstractService
     */
    protected function getService($service = null)
    {
    	if (!$this->service) {
    		$service = (is_string($service)) ?: $this->getServiceName();
    		$sl = $this->getServiceLocator();
    		$this->service = $sl->get($service);
    	}
    
    	return $this->service;
    }
    
    public function getModels()
    {
    	return $this->models;
    }
    
    public function setModels($models)
    {
    	$this->models = $models;
    	return $this;
    }
    
    public function getModel()
    {
    	return $this->model;
    }
    
    public function setModel($model)
    {
    	$this->model = $model;
    	return $this;
    }
    
    public function getSearchDefaultParams()
    {
    	return $this->searchDefaultParams;
    }
    
    public function setSearchDefaultParams($searchDefaultParams)
    {
    	$this->searchDefaultParams = $searchDefaultParams;
    	return $this;
    }
    
    public function setServiceName($serviceName)
    {
    	$this->serviceName = $serviceName;
    	return $this;
    }
    
    public function getRoute()
    {
    	return $this->route;
    }
    
    public function setRoute($route)
    {
    	$this->route = $route;
    	return $this;
    }
}