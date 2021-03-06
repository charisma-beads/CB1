<?php

namespace Common\I18n\Filter;

use Laminas\Filter\AbstractFilter;
use libphonenumber\PhoneNumberUtil;
use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumberFormat;


class PhoneNumber extends AbstractFilter
{
    /**
     * @var string
     */
    protected $country;

    /**
     * @var PhoneNumberUtil
     */
    protected $libPhoneNumber;

    public function __construct($options = [])
    {
        $this->libPhoneNumber = PhoneNumberUtil::getInstance();

        if (array_key_exists('country', $options)) {
            $this->setCountry($options['country']);
        }
    }

    /**
     * @return string $country
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param string $country
     * @return $this
     */
    public function setCountry($country)
    {
        $this->country = $country;
        return $this;
    }

    public function filter($value)
    {
        try {
            $NumberProto = $this->libPhoneNumber->parse($value, $this->getCountry());
        } catch (NumberParseException $e) {
            return $value;
        }

        return $this->libPhoneNumber->format($NumberProto, PhoneNumberFormat::E164);

    }
}
