<?php
namespace Shop\Form\Product;

use Zend\Form\Form;

class Image extends Form
{
    public function init()
    {
        $this->add([
        	'name'	=> 'productImageId',
        	'type'	=> 'hidden',
        ]);
    }
}
