<?php
declare(strict_types=1);

namespace App\Entity;

use Symfony\Component\Security\Core\User\UserInterface;

class DummyUser implements UserInterface
{
    public function getRoles()
    {
        return ['ROLE_USER', 'ROLE_API', 'ROLE_DUMMY'];
    }

    public function eraseCredentials()
    {
        return null;
    }

    public function getPassword()
    {
        return 'dummyPassword';
    }

    public function getSalt()
    {
        return 'a1d2h4f3a2c1';
    }

    public function getUsername()
    {
        return 'dummy';
    }
}
