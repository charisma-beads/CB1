<?php

namespace Common\View;

use Zend\Form\View\Helper\AbstractHelper;


class Alert extends AbstractHelper
{

    /**
     * @var string
     */
    protected $format = '<div class="alert %s alert-dismissable"><button type="button" class="close" data-dismiss="alert">&times;</button>%s</div>';

    /**
     * Display an Informational Alert
     *
     * @param  string $alert
     * @param  bool $isBlock
     * @return string
     */
    public function info($alert, $isBlock = false)
    {
        return $this->render($alert, $isBlock, 'alert-info');
    }

    /**
     * Display an Error Alert
     *
     * @param  string $alert
     * @param  bool $isBlock
     * @return string
     */
    public function danger($alert, $isBlock = false)
    {
        return $this->render($alert, $isBlock, 'alert-danger');
    }

    /**
     * Display a Success Alert
     *
     * @param  string $alert
     * @param  bool $isBlock
     * @return string
     */
    public function success($alert, $isBlock = false)
    {
        return $this->render($alert, $isBlock, 'alert-success');
    }

    /**
     * Display a Warning Alert
     *
     * @param  string $alert
     * @param  bool $isBlock
     * @return string
     */
    public function warning($alert, $isBlock = false)
    {
        return $this->render($alert, $isBlock);
    }

    /**
     * Render an Alert
     *
     * @param  string $alert
     * @param  bool $isBlock
     * @param  string $class
     * @return string
     */
    public function render($alert, $isBlock = false, $class = '')
    {
        if ($isBlock) {
            $class .= ' alert-block';
        }
        $class = trim($class);

        return sprintf($this->format, $class, $alert);
    }

    /**
     * Invoke Alert
     *
     * @param  string $alert
     * @param  bool $isBlock
     * @param  string $class
     * @return string|self
     */
    public function __invoke($alert = null, $isBlock = false, $class = '')
    {
        if ($alert) {
            return $this->render($alert, $isBlock, $class);
        }

        return $this;
    }
}
