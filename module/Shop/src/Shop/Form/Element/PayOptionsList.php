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

use Shop\Options\CartOptions;
use Zend\Form\Element\Radio;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;

/**
 * Class PayOptionsList
 *
 * @package Shop\Form\Element
 */
class PayOptionsList extends Radio implements ServiceLocatorAwareInterface
{
    use ServiceLocatorAwareTrait;

    /**
     * @var bool
     */
    protected $addPrefix = false;

    public function setOptions($options)
    {
        parent::setOptions($options);

        if (isset($options['add_prefix'])) {
            $this->setAddPrefix($options['add_prefix']);
        }

        return $this;
    }

    /**
     * @return array
     */
    public function getValueOptions()
    {
        return ($this->valueOptions) ?: $this->getPaymentOptions();
    }

    /**
     * @return array
     */
    public function getPaymentOptions()
    {
        $options = $this->getServiceLocator()
            ->getServiceLocator()
            ->get(CartOptions::class);
        
        $options = $options->toArray();
        $optionsArray = [];
        
        foreach($options as $key => $value) {
            $ex_key = explode('_', $key);

            if ('credit' === $ex_key[1]) {
                $ex_key[1] = 'debit / ' . $ex_key[1];
            }
            
            if ('pay' === $ex_key[0]  && true == $value) {
                $ex_key[0] = $ex_key[0] . ' by';
                $prefix = ($this->isAddPrefix()) ? '<i></i>' : '';
                $optionsArray[$key] =  $prefix . ucwords(implode(' ', $ex_key));
            }
        }
        
        return $optionsArray;
    }

    /**
     * @return boolean
     */
    public function isAddPrefix(): bool
    {
        return $this->addPrefix;
    }

    /**
     * @param boolean $addPrefix
     * @return $this
     */
    public function setAddPrefix($addPrefix)
    {
        $this->addPrefix = $addPrefix;
        return $this;
    }

}
