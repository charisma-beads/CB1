<?php
namespace Application\Controller;

use Exception;
use Zend\Mvc\Controller\AbstractActionController;


abstract class AbstractController extends AbstractActionController
{   
    /**
     * Sets a exception message for flash plugin.
     *
     * @param Exception $exception
     */
    public function setExceptionMessages(Exception $exception)
    {
    	$this->flashMessenger()->addErrorMessage(array(
    			'message' => $exception->getMessage(),
    			'title'   => 'Error!'
    	));
    
    	$e = $exception->getPrevious();
    
    	if ($e) {
    		while ($e) {
    			$this->flashMessenger()->addMessage($e->getMessage());
    			$e = $e->getPrevious();
    		}
    	}
    }
}
