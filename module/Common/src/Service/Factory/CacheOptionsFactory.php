<?php

namespace Common\Service\Factory;

use Common\Options\CacheOptions;
use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;

class CacheOptionsFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator): CacheOptions
    {
        $config = $serviceLocator->get('config');
        $options = $config['common']['cache'] ?? [];

        return new CacheOptions($options);
    }
}