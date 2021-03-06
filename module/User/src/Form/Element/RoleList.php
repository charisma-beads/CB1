<?php

declare(strict_types=1);

namespace User\Form\Element;

use Laminas\Form\Element\Select;
use Laminas\ServiceManager\ServiceLocatorAwareInterface;
use Laminas\ServiceManager\ServiceLocatorAwareTrait;

class RoleList extends Select implements ServiceLocatorAwareInterface
{
    use ServiceLocatorAwareTrait;

    protected $emptyOption = '---Please choose a user role---';

    public function getValueOptions(): array
    {
        return ($this->valueOptions) ?: $this->getRoles();
    }

    public function getRoles(): array
    {
        $config = $this->getServiceLocator()
            ->getServiceLocator()
            ->get('config');
        $roles = $config['user']['acl']['roles'];
        $roleOptions = [];

        foreach ($roles as $key => $value) {
            $roleOptions[] = [
                'label' => $value['label'],
                'value' => $key,
            ];
        }

        return $roleOptions;
    }
}
