<?php
namespace Shop\Hydrator\Customer;

use UthandoCommon\Hydrator\AbstractHydrator;
use UthandoCommon\Hydrator\Strategy\DateTime as DateTimeStrategy;

class Address extends AbstractHydrator
{
	public Function __construct()
	{
		parent::__construct();
		
		$dateTime = new DateTimeStrategy();
		
		$this->addStrategy('dateCreated', $dateTime);
		$this->addStrategy('dateModified', $dateTime);
	}

	/**
	 *
	 * @param \Shop\Model\Customer\Address $object        	
	 * @return array $data
	 */
	public function extract($object)
	{
		return [
			'customerAddressId'  => $object->getCustomerAddressId(),
		    'customerId'         => $object->getCustomerId(),
		    'countryId'          => $object->getCountryId(),
		    'provinceId'         => $object->getProvinceId(),
			'address1'           => $object->getAddress1(),
			'address2'           => $object->getAddress2(),
			'address3'           => $object->getAddress3(),
			'city'               => $object->getCity(),
			'county'             => $object->getCounty(),
			'postcode'           => $object->getPostcode(),
			'phone'              => $object->getPhone(),
			'email'              => $object->getEmail(),
			'dateCreated'        => $this->extractValue('dateCreated', $object->getDateCreated()),
			'dateModified'       => $this->extractValue('dateModified', $object->getDateModified())
		];
	}
}
