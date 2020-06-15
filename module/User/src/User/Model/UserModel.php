<?php

declare(strict_types=1);

namespace User\Model;

use Common\Model\DateCreatedTrait;
use Common\Model\DateModifiedTrait;
use Common\Model\ModelInterface;
use Common\Model\Model;
use Zend\Permissions\Acl\Role\RoleInterface;
use Zend\Math\Rand;

class UserModel implements RoleInterface, ModelInterface
{
    use Model,
        UserIdTrait,
        DateCreatedTrait,
        DateModifiedTrait;

    const PASSWORD_LENGTH = 12;

    /**
     * @var string
     */
    protected $firstname;

    /**
     * @var string
     */
    protected $lastname;

    /**
     * @var string
     */
    protected $email;

    /**
     * @var string
     */
    protected $passwd;

    /**
     * @var string
     */
    protected $role;

    /**
     * @var bool
     */
    protected $active = false;

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): UserModel
    {
        $this->email = $email;
        return $this;
    }

    public function getPasswd(): ?string
    {
        return $this->passwd;
    }

    public function setPasswd(?string $passwd): UserModel
    {
        $this->passwd = $passwd;
        return $this;
    }

    public function generatePassword(): void
    {
        $password = Rand::getString(
            self::PASSWORD_LENGTH,
            'ABCDEFGHIJKLMNPQRSTUVWXYZabcdefghijklmnpqrstuvwxyz123456789'
        );

        $password = str_split($password, 3);
        $password = implode('-', $password);

        $this->setPasswd($password);
    }

    public function getRoleId(): ?string
    {
        return $this->getRole();
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(?string $role): UserModel
    {
        $this->role = $role;
        return $this;
    }

    public function getFullName(): string
    {
        return $this->getFirstname() . ' ' . $this->getLastname();
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): UserModel
    {
        $this->firstname = $firstname;
        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): UserModel
    {
        $this->lastname = $lastname;
        return $this;
    }

    public function getLastNameFirst(): string
    {
        return $this->getLastname() . ', ' . $this->getFirstname();
    }

    public function isActive(): string
    {
        return (true === $this->getActive()) ? 'yes' : 'no';
    }

    public function getActive(): bool
    {
        return $this->active;
    }

    public function setActive(bool $active): UserModel
    {
        $this->active = $active;
        return $this;
    }
}
