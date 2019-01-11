<?php

namespace App\Presentation\Security;

use Symfony\Component\Security\Core\User\UserInterface;

class User implements UserInterface
{
    /**
     * @var int
     */
    private $id;

    /**
     * User constructor.
     * @param int $id
     */
    public function __construct(int $id)
    {
        $this->id = $id;
    }

    /**
     * @inheritdoc
     */
    public function getRoles()
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    public function getPassword()
    {
        return '';
    }

    /**
     * @inheritdoc
     */
    public function getSalt()
    {
    }

    /**
     * @inheritdoc
     */
    public function getUsername()
    {
        return $this->id;
    }

    /**
     * @inheritdoc
     */
    public function eraseCredentials()
    {
    }
}