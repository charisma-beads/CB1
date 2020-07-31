<?php

namespace Common\Event;

use Laminas\Http\PhpEnvironment\Response;
use Laminas\Mvc\ResponseSender\AbstractResponseSender;
use Laminas\Mvc\ResponseSender\SendResponseEvent;


class TidyResponseSender extends AbstractResponseSender
{
    /**
     * Tidy config array
     *
     * @var array
     */
    protected $config = [];

    /**
     * @var bool
     */
    protected $xmlHttpRequest;

    /**
     * Send content
     *
     * @param  SendResponseEvent $event
     * @return $this
     */
    public function sendContent(SendResponseEvent $event)
    {
        if ($event->contentSent()) {
            return $this;
        }

        $response = $event->getResponse();

        $tidy = new \tidy();
        $tidy->parseString($response->getContent(), $this->config);
        //$tidy->cleanRepair();

        echo $tidy;

        $event->setContentSent();

        return $this;
    }

    /**
     * TidyResponseSender constructor.
     *
     * @param array $config
     * @param bool $xmlHttpRequest
     */
    public function __construct(array $config, $xmlHttpRequest = false)
    {
        $this->xmlHttpRequest = $xmlHttpRequest;
        $this->config = $config;
    }

    /**
     * @param SendResponseEvent $event
     * @return $this
     */
    public function __invoke(SendResponseEvent $event)
    {
        $response = $event->getResponse();

        if (!class_exists('tidy') || !$response instanceof Response || $response->getHeaders()->count() > 0 || $this->xmlHttpRequest) {
            return $this;
        }

        $this->sendHeaders($event)
            ->sendContent($event);

        $event->stopPropagation(true);

        return $this;
    }
}
