<?php

declare(strict_types=1);

namespace ShopDomPdf\Form;

use TwbBundle\Form\View\Helper\TwbBundleForm;
use Common\Hydrator\Strategy\CollectionToArrayStrategy;
use ShopDomPdf\Options\PdfOptions;
use Laminas\Filter\StringTrim;
use Laminas\Filter\StripTags;
use Laminas\Filter\ToNull;
use Laminas\Form\Element\Collection;
use Laminas\Form\Element\Number;
use Laminas\Form\Element\Select;
use Laminas\Form\Element\Text;
use Laminas\Form\Fieldset;
use Laminas\InputFilter\InputFilterProviderInterface;
use Laminas\Hydrator\ClassMethods;


class PdfOptionsFieldSet extends Fieldset implements InputFilterProviderInterface
{
    public function __construct($name = null, $options = [])
    {
        parent::__construct($name, $options);

        $hydrator = new ClassMethods();
        $hydrator->addStrategy('header_lines', new CollectionToArrayStrategy());
        $hydrator->addStrategy('footer_lines', new CollectionToArrayStrategy());

        $this->setHydrator($hydrator)
            ->setObject(new PdfOptions());
    }

    public function init()
    {
        $this->add([
            'name' => 'paper_size',
            'type' => Text::class,
            'options' => [
                'label' => 'Paper Size',
                'column-size' => 'md-8',
                'twb-layout' => TwbBundleForm::LAYOUT_HORIZONTAL,
                'label_attributes' => [
                    'class' => 'col-md-4',
                ],
            ],
        ]);

        $this->add([
            'name' => 'paper_orientation',
            'type' => Select::class,
            'options' => [
                'label' => 'Paper Orientation',
                'twb-layout' => TwbBundleForm::LAYOUT_HORIZONTAL,
                'label_attributes' => [
                    'class' => 'col-md-4',
                ],
                'value_options' => [
                    'portrait' => 'Portrait',
                    'landscape' => 'Landscape',
                ],
                'column-size' => 'md-8',
            ],
        ]);

        $this->add([
            'name' => 'top_margin',
            'type' => Number::class,
            'options' => [
                'label' => 'Top Margin',
                'twb-layout' => TwbBundleForm::LAYOUT_HORIZONTAL,
                'label_attributes' => [
                    'class' => 'col-md-4',
                ],
                'column-size' => 'md-8',
            ],
        ]);

        $this->add([
            'name' => 'right_margin',
            'type' => Number::class,
            'options' => [
                'label' => 'Right Margin',
                'twb-layout' => TwbBundleForm::LAYOUT_HORIZONTAL,
                'label_attributes' => [
                    'class' => 'col-md-4',
                ],
                'column-size' => 'md-8',
            ],
        ]);

        $this->add([
            'name' => 'bottom_margin',
            'type' => Number::class,
            'options' => [
                'label' => 'Bottom Margin',
                'twb-layout' => TwbBundleForm::LAYOUT_HORIZONTAL,
                'label_attributes' => [
                    'class' => 'col-md-4',
                ],
                'column-size' => 'md-8',
            ],
        ]);

        $this->add([
            'name' => 'left_margin',
            'type' => Number::class,
            'options' => [
                'label' => 'Left Margin',
                'twb-layout' => TwbBundleForm::LAYOUT_HORIZONTAL,
                'label_attributes' => [
                    'class' => 'col-md-4',
                ],
                'column-size' => 'md-8',
            ],
        ]);

        $this->add([
            'name' => 'base_path',
            'type' => Text::class,
            'options' => [
                'label' => 'Base Path',
                'twb-layout' => TwbBundleForm::LAYOUT_HORIZONTAL,
                'column-size' => 'md-8',
                'label_attributes' => [
                    'class' => 'col-md-4',
                ],
            ],
        ]);

        $this->add([
            'name' => 'file_name',
            'type' => Text::class,
            'options' => [
                'label' => 'File Name',
                'twb-layout' => TwbBundleForm::LAYOUT_HORIZONTAL,
                'column-size' => 'md-8',
                'label_attributes' => [
                    'class' => 'col-md-4',
                ],
            ],
        ]);

        $this->add([
            'type' => Collection::class,
            'name' => 'header_lines',
            'options' => [
                'label' => 'Add header lines to PDF',
                'label_options' => [
                    'disable_html_escape' => true,
                ],
                'count' => 0,
                'should_create_template' => true,
                'allow_add' => true,
                'target_element' => [
                    'type' => PdfTextLineFieldSet::class,
                ],
            ],
            'attributes' => [
                'class' => 'col-md-12',
            ],
        ]);

        $this->add([
            'type' => Collection::class,
            'name' => 'footer_lines',
            'options' => [
                'label' => 'Add footer lines to PDF',
                'label_options' => [
                    'disable_html_escape' => true,
                ],
                'count' => 0,
                'should_create_template' => true,
                'allow_add' => true,
                'target_element' => [
                    'type' => PdfTextLineFieldSet::class,
                ],
            ],
            'attributes' => [
                'class' => 'col-md-12',
            ],
        ]);
    }

    public function getInputFilterSpecification()
    {
        return [
            'paper_size' => [
                'required' => true,
                'filters' => [
                    ['name' => StripTags::class],
                    ['name' => StringTrim::class],
                ],
            ],
            'paper_orientation' => [
                'required' => true,
                'filters' => [
                    ['name' => StripTags::class],
                    ['name' => StringTrim::class],
                ],
            ],
            'base_path' => [
                'required' => false,
                'filters' => [
                    ['name' => StripTags::class],
                    ['name' => StringTrim::class],
                ],
            ],
            'file_name' => [
                'required' => false,
                'filters' => [
                    ['name' => StripTags::class],
                    ['name' => StringTrim::class],
                    ['name' => ToNull::class],
                ],
            ],
        ];
    }
}
