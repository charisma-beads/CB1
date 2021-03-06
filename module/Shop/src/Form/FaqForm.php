<?php

namespace Shop\Form;

use Shop\Form\Element\FaqList;
use TwbBundle\Form\View\Helper\TwbBundleForm;
use Common\Mapper\AbstractNestedSet as NestedSet;
use Laminas\Form\Element\Hidden;
use Laminas\Form\Element\Radio;
use Laminas\Form\Element\Text;
use Laminas\Form\Element\Textarea;
use Laminas\Form\Form;

/**
 * Class Faq
 *
 * @package Shop\Form
 */
class FaqForm extends Form
{
    /**
     * @var int
     */
    protected $faqId;

    /**
     * @param array|\Traversable $options
     * @return \Laminas\Form\Element|\Laminas\Form\ElementInterface
     */
    public function setOptions($options)
    {
        if (isset($options['faqId'])) {
            $this->faqId = $options['faqId'];
        }

        return parent::setOptions($options);
    }

    public function init()
    {
        $this->add([
            'name'	=> 'faqId',
            'type'	=> Hidden::class,
        ]);

        $this->add([
            'name'			=> 'question',
            'type'			=> Text::class,
            'attributes'	=> [
                'placeholder'		=> 'Question',
                'autofocus'			=> true,
                'autocapitalize'	=> 'on',
            ],
            'options'		=> [
                'label'	=> 'Question',
                'twb-layout' => TwbBundleForm::LAYOUT_HORIZONTAL,
                'column-size' => 'sm-10',
                'label_attributes' => [
                    'class' => 'col-sm-2',
                ],
            ]
        ]);

        $this->add([
            'name'			=> 'answer',
            'type'			=> Textarea::class,
            'attributes'	=> [
                'placeholder'		=> 'Answer',
                'autofocus'			=> true,
                'autocapitalize'	=> 'off',
                'class'             => 'editable-textarea',
            ],
            'options'		=> [
                'label'			=> 'Answer',
                'twb-layout' => TwbBundleForm::LAYOUT_HORIZONTAL,
                'column-size' => 'sm-10',
                'label_attributes' => [
                    'class' => 'col-sm-2',
                ],
            ],
        ]);

        $this->add([
            'name'			=> 'parent',
            'type'			=> FaqList::class,
            'attributes'	=> [
                'class' => 'input-xlarge',
            ],
            'options'		=> [
                'label'			=> 'Parent',
                'required'		=> false,
                'add_top'       => true,
                'twb-layout'    => TwbBundleForm::LAYOUT_HORIZONTAL,
                'column-size'   => 'sm-10',
                'label_attributes' => [
                    'class' => 'col-sm-2',
                ],
            ],
        ]);

        $faqInsertOptions = [
            NestedSet::INSERT_NODE	=> 'insert after this faq.',
            //NestedSet::INSERT_CHILD	=> 'insert as a new sub-faq at the top.',

        ];

        if ($this->getFaqId()) {
            $faqInsertOptions['noInsert'] = [
                'value' => 'noInsert',
                'selected' => true,
                'label' => 'no change',
            ];
        }

        $this->add([
            'name'			=> 'faqInsertType',
            'type'			=> Radio::class,
            'options'		=> [
                'required'		=> true,
                'value_options' => array_reverse($faqInsertOptions, true),
                'twb-layout' => TwbBundleForm::LAYOUT_HORIZONTAL,
                'column-size' => 'sm-10 col-sm-offset-2',
            ],
        ]);
    }

    /**
     * @return int
     */
    public function getFaqId()
    {
        return $this->faqId;
    }

    /**
     * @param int $faqId
     * @return $this
     */
    public function setFaqId($faqId)
    {
        $this->faqId = $faqId;
        return $this;
    }
}
