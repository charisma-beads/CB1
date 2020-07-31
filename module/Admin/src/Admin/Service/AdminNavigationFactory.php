<?php declare(strict_types=1);

namespace Admin\Service;

use Laminas\Navigation\Service\AbstractNavigationFactory;

/**
 * Class AdminNavigationFactory
 *
 * @package Admin\Service
 */
class AdminNavigationFactory extends AbstractNavigationFactory
{
    protected function getName(): string
    {
        return 'admin';
    }
}
