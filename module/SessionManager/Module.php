<?php declare(strict_types=1);

namespace SessionManager;

use Common\Config\ConfigInterface;
use Common\Config\ConfigTrait;
use SessionManager\Event\RouteListener;
use Laminas\Console\Adapter\AdapterInterface as Console;
use Laminas\Mvc\MvcEvent;

class Module implements ConfigInterface
{
    use ConfigTrait;

    /**
     * @param MvcEvent $event
     */
    public function onBootstrap(MvcEvent $event): void
    {
        $app            = $event->getApplication();
        $eventManager   = $app->getEventManager();
        $session        = new RouteListener();
        $session->attach($eventManager);
    }

    public function getConfig(): array
    {
        defined('APPLICATION_PATH') or define('APPLICATION_PATH', realpath(dirname('./')));

        return include __DIR__ . '/config/module.config.php';
    }

    /**
     * @param Console $console
     * @return array
     */
    public function getConsoleUsage(Console $console)
    {
        return [
            'session gc' => 'initiate garbage collection on sessions in database.',
        ];
    }
}
