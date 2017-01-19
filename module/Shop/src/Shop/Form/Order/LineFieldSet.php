<?php
/**
 * Uthando CMS (http://www.shaunfreeman.co.uk/)
 *
 * @package   Shop\Form\Order
 * @author    Shaun Freeman <shaun@shaunfreeman.co.uk>
 * @copyright Copyright (c) 2014 Shaun Freeman. (http://www.shaunfreeman.co.uk)
 * @license   see LICENSE.txt
 */

namespace Shop\Form\Order;

use TwbBundle\Form\Element\StaticElement;
use TwbBundle\Form\View\Helper\TwbBundleForm;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Shop\Model\Order\Line as LineModel;
use Shop\Hydrator\Order\Line as LineHydrator;

/**
 * Class LineFieldSet
 *
 * @package Shop\Form\Order
 */
class LineFieldSet extends Fieldset implements InputFilterProviderInterface
{
    /**
     * Constructor
     *
     * @param null|string $name
     * @param array $options
     */
    public function __construct($name = null, $options = [])
    {
        parent::__construct($name, $options);
        
        $this->setHydrator(new LineHydrator())
            ->setObject(new LineModel());
    }

    /**
     * set up elements
     */
    public function init()
    {
        $this->add([
            'name' => 'id',
            'type' => 'Hidden',
        ]);

        $this->add([
            'name' => 'productId',
            'type' => 'hidden',
        ]);

        $this->add([
            'name' => 'quantity',
            'type' => 'Number',
            'options' => [
                'label' => 'Quantity:',
                'required' => true,
            ],
            'attributes' => [
                'min'  => '0',
                'step' => '1',
                'value' => 1,
            ],
        ]);
    }

    /**
     * @return array
     */
    public function getInputFilterSpecification()
    {
        return [];
    }
}
