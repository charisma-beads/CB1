<?php

namespace Newsletter\Form\Element;

use Common\Service\ServiceManager;
use Newsletter\Model\NewsletterModel;
use Newsletter\Service\NewsletterService;
use Zend\Form\Element\Select;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;


class NewsletterList extends Select implements ServiceLocatorAwareInterface
{
    use ServiceLocatorAwareTrait;

    /**
     * @return array
     */
    public function getValueOptions()
    {
        return ($this->valueOptions) ?: $this->getNewsletters();
    }

    /**
     * return array
     */
    public function getNewsletters()
    {
        $newsletters = $this->getServiceLocator()
            ->getServiceLocator()
            ->get(ServiceManager::class)
            ->get(NewsletterService::class)
            ->fetchAll();

        $newsletterOptions = [];

        /* @var $newsletter NewsletterModel */
        foreach($newsletters as $newsletter) {
            $newsletterOptions[$newsletter->getNewsletterId()] = $newsletter->getName();
        }

        return $newsletterOptions;
    }
}
