<?php

namespace User\Form\Settings;

use User\Options\LoginOptions;
use Laminas\Filter\Boolean;
use Laminas\Filter\StringTrim;
use Laminas\Filter\StripTags;
use Laminas\Filter\ToInt;
use Laminas\Form\Element\Checkbox;
use Laminas\Form\Element\Number;
use Laminas\Form\Fieldset;
use Laminas\Hydrator\ClassMethods;
use Laminas\I18n\Validator\IsInt;
use Laminas\InputFilter\InputFilterProviderInterface;

class LoginFieldSet extends Fieldset implements InputFilterProviderInterface
{
    public function __construct($name = null, $options = [])
    {
        parent::__construct($name, $options);

        $this->setHydrator(new ClassMethods())
            ->setObject(new LoginOptions());
    }

    public function init(): void
    {
        $this->add([
            'name' => 'limit_login',
            'type' => Checkbox::class,
            'options' => [
                'label' => 'Limit Logins',
                'use_hidden_element' => true,
                'checked_value' => '1',
                'unchecked_value' => '0',
                'required' => false,
                'column-size' => 'sm-6 col-sm-offset-6',
            ],
        ]);

        $this->add([
            'name' => 'max_login_attempts',
            'type' => Number::class,
            'options' => [
                'label' => 'Max Login Attempts',
                'column-size' => 'sm-6',
                'label_attributes' => [
                    'class' => 'col-sm-6',
                ],
            ],
        ]);

        $this->add([
            'name' => 'ban_time',
            'type' => Number::class,
            'options' => [
                'label' => 'Ban Time',
                'column-size' => 'sm-6',
                'label_attributes' => [
                    'class' => 'col-sm-6',
                ],
            ],
        ]);

        $this->add([
            'name' => 'login_min_password_length',
            'type' => Number::class,
            'options' => [
                'label' => 'Login Min Password Length',
                'column-size' => 'sm-6',
                'label_attributes' => [
                    'class' => 'col-sm-6',
                ],
            ],
        ]);

        $this->add([
            'name' => 'login_max_password_length',
            'type' => Number::class,
            'options' => [
                'label' => 'Login Max Password Length',
                'column-size' => 'sm-6',
                'label_attributes' => [
                    'class' => 'col-sm-6',
                ],
            ],
        ]);

        $this->add([
            'name' => 'register_min_password_length',
            'type' => Number::class,
            'options' => [
                'label' => 'Register Min Password Length',
                'column-size' => 'sm-6',
                'label_attributes' => [
                    'class' => 'col-sm-6',
                ],
            ],
        ]);

        $this->add([
            'name' => 'register_max_password_length',
            'type' => Number::class,
            'options' => [
                'label' => 'Register Max Password Length',
                'column-size' => 'sm-6',
                'label_attributes' => [
                    'class' => 'col-sm-6',
                ],
            ],
        ]);

        $this->add([
            'name' => 'email_validate_options',
            'type' => EmailValidateOptionsFieldSet::class,
            'attributes' => [
                //'class' => 'col-sm-12',
            ],
            'options' => [
                //'label' => 'Email Validate Options',
            ],
        ]);
    }

    public function getInputFilterSpecification(): array
    {
        return [
            'limit_login' => [
                'required' => true,
                'allow_empty' => true,
                'filters' => [
                    ['name' => StripTags::class],
                    ['name' => StringTrim::class],
                    ['name' => Boolean::class, 'options' => [
                        'type' => Boolean::TYPE_ZERO_STRING,
                    ]],
                ],
            ],
            'max_login_attempts' => [
                'required' => true,
                'filters' => [
                    ['name' => StripTags::class],
                    ['name' => StringTrim::class],
                    ['name' => ToInt::class],
                ],
                'validators' => [
                    ['name' => IsInt::class],
                ],
            ],
            'ban_time' => [
                'required' => true,
                'filters' => [
                    ['name' => StripTags::class],
                    ['name' => StringTrim::class],
                    ['name' => ToInt::class],
                ],
                'validators' => [
                    ['name' => IsInt::class],
                ],
            ],
            'login_min_password_length' => [
                'required' => true,
                'filters' => [
                    ['name' => StripTags::class],
                    ['name' => StringTrim::class],
                    ['name' => ToInt::class],
                ],
                'validators' => [
                    ['name' => IsInt::class],
                ],
            ],
            'login_max_password_length' => [
                'required' => true,
                'filters' => [
                    ['name' => StripTags::class],
                    ['name' => StringTrim::class],
                    ['name' => ToInt::class],
                ],
                'validators' => [
                    ['name' => IsInt::class],
                ],
            ],
            'register_min_password_length' => [
                'required' => true,
                'filters' => [
                    ['name' => StripTags::class],
                    ['name' => StringTrim::class],
                    ['name' => ToInt::class],
                ],
                'validators' => [
                    ['name' => IsInt::class],
                ],
            ],
            'register_max_password_length' => [
                'required' => true,
                'filters' => [
                    ['name' => StripTags::class],
                    ['name' => StringTrim::class],
                    ['name' => ToInt::class],
                ],
                'validators' => [
                    ['name' => IsInt::class],
                ],
            ],
        ];
    }
}
