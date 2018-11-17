<?php
/**
 * Uthando CMS (http://www.shaunfreeman.co.uk/)
 *
 * @package   Shop\Form\Settings
 * @author    Shaun Freeman <shaun@shaunfreeman.co.uk>
 * @copyright Copyright (c) 2014 Shaun Freeman. (http://www.shaunfreeman.co.uk)
 * @license   see LICENSE.txt
 */

namespace Shop\Form\Settings;

use Shop\Options\InvoiceOptions;
use Zend\Filter\StringTrim;
use Zend\Filter\StripTags;
use Zend\Form\Element\Text;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

/**
 * Class InvoiceFieldSet
 *
 * @package Shop\Form\Settings
 */
class InvoiceFieldSet extends Fieldset implements InputFilterProviderInterface
{
    public function __construct($name = null, $options = [])
    {
        parent::__construct($name, $options);

        $this->setHydrator(new ClassMethods())
            ->setObject(new InvoiceOptions());
    }

    public function init()
    {
        $this->add([
            'name' => 'font_size',
            'type' => Text::class,
            'options' => [
                'label' => 'Font Size',
                'column-size' => 'md-8',
                'label_attributes' => [
                    'class' => 'col-md-4',
                ],
                'help-block' => 'Can be any css font-size value (eg. px,pt or %)',
            ],
        ]);

        $this->add([
            'name' => 'panel_title_font_size',
            'type' => Text::class,
            'options' => [
                'label' => 'Panel Title Font Size',
                'column-size' => 'md-8',
                'label_attributes' => [
                    'class' => 'col-md-4',
                ],
                'help-block' => 'Can be any css font-size value (eg. px,pt or %)',
            ],
        ]);

        $this->add([
            'name' => 'footer_font_size',
            'type' => Text::class,
            'options' => [
                'label' => 'Footer Font Size',
                'column-size' => 'md-8',
                'label_attributes' => [
                    'class' => 'col-md-4',
                ],
                'help-block' => 'Can be any css font-size value (eg. px,pt or %)',
            ],
        ]);
    }

    public function getInputFilterSpecification()
    {
        return [
            'font_size' => [
                'required' => true,
                'filters' => [
                    ['name' => StripTags::class],
                    ['name' => StringTrim::class],
                ],
            ],
            'panel_title_font_size' => [
                'required' => true,
                'filters' => [
                    ['name' => StripTags::class],
                    ['name' => StringTrim::class],
                ],
            ],
            'footer_font_size' => [
                'required' => true,
                'filters' => [
                    ['name' => StripTags::class],
                    ['name' => StringTrim::class],
                ],
            ],
        ];
    }
}