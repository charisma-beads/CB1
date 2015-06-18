<?php
/**
 * Uthando CMS (http://www.shaunfreeman.co.uk/)
 *
 * @package   Shop\Form\Element
 * @author    Shaun Freeman <shaun@shaunfreeman.co.uk>
 * @copyright Copyright (c) 2014 Shaun Freeman. (http://www.shaunfreeman.co.uk)
 * @license   see LICENSE.txt
 */

namespace Shop\Form\Element;

use Zend\Form\Element\Select;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;

class ProductSizeList extends Select implements ServiceLocatorAwareInterface
{
    use ServiceLocatorAwareTrait;
    
    protected $emptyOption = '---Please select a size---';
    
    public function init()
    {
        $sizes = $this->getServiceLocator()
            ->getServiceLocator()
            ->get('UthandoServiceManager')
            ->get('ShopProductSize')
            ->fetchAll();
        
    	$sizeOptions = [];
    	
    	/* @var $size \Shop\Model\Product\Size */
    	foreach($sizes as $size) {
    		$sizeOptions[$size->getProductSizeId()] = $size->getSize(); 
    	}
        
        $this->setValueOptions($sizeOptions);
    }

}