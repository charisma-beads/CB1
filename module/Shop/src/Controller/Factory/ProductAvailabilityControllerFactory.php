<?php

declare(strict_types=1);

namespace Shop\Controller\Factory;

use Shop\Controller\ProductAvailabilityController;
use Shop\Service\ProductAvailabilityService;
use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;

final class ProductAvailabilityControllerFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator): ProductAvailabilityController
    {
        /** @var ProductAvailabilityService $productAvailabilityService */
        $productAvailabilityService = $serviceLocator->get(ProductAvailabilityService::class);

        return new ProductAvailabilityController($productAvailabilityService);
    }
}

