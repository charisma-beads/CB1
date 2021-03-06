<?php

namespace Common\Service;

use Laminas\ServiceManager\ServiceLocatorAwareInterface;
use Laminas\ServiceManager\ServiceLocatorAwareTrait;
use Common\Model\ModelInterface;


class FormService implements ServiceLocatorAwareInterface
{
    use ServiceLocatorAwareTrait;

    /**
     * @var array
     */
    protected $options;

    /**
     * @param array $options
     */
    public function __construct($options = [])
    {
        if (!empty($options)) {
            $this->setOptions($options);
        }
    }

    /**
     * @return array|null:
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param array $options
     * @return \Common\Service\FormService
     */
    public function setOptions(array $options)
    {
        $this->options = $options;
        return $this;
    }

    /**
     * @param string $key
     * @return string|null
     */
    public function getOption($key)
    {
        if (!isset($this->options[$key])) {
            return null;
        }

        return $this->options[$key];
    }

    /**
     * @param string $key
     * @param string $value
     * @return \Common\Service\FormService
     */
    public function setOption($key, $value)
    {
        $this->options[$key] = $value;
        return $this;
    }

    /**
     * @param string $name
     * @param ModelInterface $model
     * @param array $data
     * @return \Laminas\Form\Form
     */
    public function getForm($name, $model = null, $data = null)
    {
        $form = $this->getFromServiceManager($name);

        if ($model instanceof ModelInterface) {
            $form->bind($model);
        }

        if (is_array($data)) {
            $form->setData($data);
        }

        return $form;
    }

    /**
     * @param string $name
     * @return \Laminas\Form\Form
     */
    public function getFromServiceManager($name)
    {
        $formManager = $this->getServiceLocator()
            ->get('FromElementManager');
        return $formManager->get($name, $this->getOptions());
    }
}
