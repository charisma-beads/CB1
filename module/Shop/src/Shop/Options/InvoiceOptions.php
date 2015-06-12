<?php
/**
 * Uthando CMS (http://www.shaunfreeman.co.uk/)
 *
 * @package   Shop\Options
 * @author    Shaun Freeman <shaun@shaunfreeman.co.uk>
 * @copyright Copyright (c) 2014 Shaun Freeman. (http://www.shaunfreeman.co.uk)
 * @license   see LICENSE.txt
 */

namespace Shop\Options;

use Zend\Stdlib\AbstractOptions;

/**
 * Class InvoiceOptions
 *
 * @package Shop\Options
 */
class InvoiceOptions extends AbstractOptions
{
    /**
     * @var string
     */
    protected $merchantName;
    /**
     * @var string
     */
    protected $fontSize;

    /**
     * @var string
     */
    protected $panelTitleFontSize;

    /**
     * @var string
     */
    protected $footerFontSize;

    /**
     * @return string
     */
    public function getMerchantName()
    {
        return $this->merchantName;
    }

    /**
     * @param string $merchantName
     * @return $this
     */
    public function setMerchantName($merchantName)
    {
        $this->merchantName = $merchantName;
        return $this;
    }

    /**
     * @return string
     */
    public function getFontSize()
    {
        return $this->fontSize;
    }

    /**
     * @param string $fontSize
     * @return $this
     */
    public function setFontSize($fontSize)
    {
        $this->fontSize = $fontSize;
        return $this;
    }

    /**
     * @return string
     */
    public function getPanelTitleFontSize()
    {
        return $this->panelTitleFontSize;
    }

    /**
     * @param string $panelTitleFontSize
     * @return $this
     */
    public function setPanelTitleFontSize($panelTitleFontSize)
    {
        $this->panelTitleFontSize = $panelTitleFontSize;
        return $this;
    }

    /**
     * @return string
     */
    public function getFooterFontSize()
    {
        return $this->footerFontSize;
    }

    /**
     * @param string $footerFontSize
     * @return $this
     */
    public function setFooterFontSize($footerFontSize)
    {
        $this->footerFontSize = $footerFontSize;
        return $this;
    }
}