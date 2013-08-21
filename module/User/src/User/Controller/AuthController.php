<?php

namespace User\Controller;

use User\Form\LoginForm;
use User\Service\AuthenticationService;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class AuthController extends AbstractActionController
{   
    protected $auth;
    protected $form;
    
    public function setAuthService(AuthenticationService $auth)
    {
    	$this->auth = $auth;
    }
    
	public function setLoginForm(LoginForm $form)
    {
        $this->form = $form;
    }
    
    public function loginAction()
    {   
        return new ViewModel(array(
            'form' => $this->form
        ));   
    }
    
    public function logoutAction()
    {
        $this->auth->clear();
        return $this->redirect()->toRoute('application');
    }
    
    public function authenticateAction()
    {
        $request = $this->getRequest();

        if (!$request->isPost()) {
            return $this->forward()->dispatch('auth', array(
                'action' => 'login'
            ));
        }
        
        // Validate
        $form = $this->form;
        $form->setData($request->getPost());
        
        $viewModel = new ViewModel(array(
            'form' => $form
        ));
        $viewModel->setTemplate('user/auth/login.phtml');

        if (!$form->isValid()) {
        	$this->flashMessenger()->addInfoMessage(
        		'There were one or more isues with your submission. Please correct them as indicated below.'
        	);
        	
            return $viewModel; // re-render the login form
        }

        if (false === $this->auth->authenticate($form->getData())) {
            //$form->setDescription('Login failed, Please try again.');
        	$this->flashMessenger()->addInfoMessage(
        		'Login failed, Please try again.'
        	);

            return $viewModel; // re-render the login form
        }

        return $this->redirect()->toRoute('application');
    }
}