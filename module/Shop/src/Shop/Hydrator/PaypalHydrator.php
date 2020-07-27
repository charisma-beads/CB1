<?php
/**
 * Uthando CMS (http://www.shaunfreeman.co.uk/)
 *
 * @package   Shop\Hydrator\Paypal
 * @author    Shaun Freeman <shaun@shaunfreeman.co.uk>
 * @copyright Copyright (c) 2014 Shaun Freeman. (http://www.shaunfreeman.co.uk)
 * @license   see LICENSE.txt
 */

namespace Shop\Hydrator;

use PayPal\Common\PayPalModel;
use Zend\Hydrator\AbstractHydrator;

/**
 * Class PaypalHydrator
 *
 * @package Shop\Hydrator
 */
class PaypalHydrator extends AbstractHydrator
{
    const  PAYPAL_NAMESPACE = 'PayPal\\Api\\';
    
    protected $modelName;
    
    public function __construct($modelName)
    {
    	parent::__construct();
    	
    	$this->modelName = (string) $modelName;
    }
    
    /**
     * @param array $data
     * @param \PayPal\Common\PayPalModel $object
     * @return \PayPal\Common\PayPalModel $object
     */
    public function hydrate(array $data, $object)
    {
        $model = $this->getModelName();

    	if (!$object instanceof $model) {
    		return $object;
    	}
    	
        $object->fromArray($data[$this->modelName]);
    
    	return $object;
    }
    
    /**
     * @param \PayPal\Common\PayPalModel $object
     * @return array|PayPalModel
     */
    public function extract($object)
    {
        $model = $this->getModelName();
        
    	if (!$object instanceof $model) {
    		return $object;
    	}
    
    	return $object->toArray();
    }
    
    public function getModelName()
    {
        return self::PAYPAL_NAMESPACE . ucfirst($this->modelName);
    }
}