<?php
namespace User\Service\Factory;

use Zend\Navigation\Service\AbstractNavigationFactory;

class UserNavigationFactory extends AbstractNavigationFactory
{
	protected function getName()
	{
		return 'user';
	}
}
