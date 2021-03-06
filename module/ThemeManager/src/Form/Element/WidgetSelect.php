<?php

declare(strict_types=1);

namespace ThemeManager\Form\Element;

use Laminas\Form\Element\Select;
use Laminas\ServiceManager\AbstractPluginManager;
use Laminas\ServiceManager\ServiceLocatorAwareInterface;
use Laminas\ServiceManager\ServiceLocatorAwareTrait;

/**
 * Class WidgetSelect
 * @package ThemeManager\Form\Element
 * @method AbstractPluginManager getServiceLocator()
 */
class WidgetSelect  extends Select implements ServiceLocatorAwareInterface
{
    use ServiceLocatorAwareTrait;

    protected $emptyOption = '---Select Widget---';

    public function getValueOptions(): array
    {
        $options = $this->valueOptions ?: $this->getOptionList();
        return $options;
    }

    public function getOptionList(): array
    {
        $config = $this->getServiceLocator()
            ->getServiceLocator()
            ->get('config');

        $widgets = preg_grep(
            '/\\\\Widget\\\\/',
            $config['view_helpers']['invokables']
        );

        $widgetOptions= [];

        foreach($widgets as $widget) {
            $widgetOptions[] = [
                'label' => $widget,
                'value' => $widget,
            ];
        }

        return $widgetOptions;
    }
}
