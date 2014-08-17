<?php
namespace Shop\Controller\Country;

use UthandoCommon\Controller\AbstractCrudController;

class Country extends AbstractCrudController
{
	protected $searchDefaultParams = array('sort' => 'country');
	protected $serviceName = 'Shop\Service\Country';
	protected $route = 'admin/shop/country';
}
