<?php
declare(strict_types=1);

namespace App\Security;

use App\Entity\DummyUser;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Exception;

class DummyUserProvider implements UserProviderInterface
{
    /**
     * @param string $username
     * @return DummyUser|UserInterface
     * @throws Exception
     */
    public function loadUserByUsername(string $username)
    {
        if (!empty($username)) {
            return new DummyUser();
        }

        throw new Exception('TODO: fill in loadUserByUsername() inside '.__FILE__);
    }

    /**
     * @param UserInterface $user
     * @return UserInterface|void
     * @throws Exception
     */
    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof DummyUser) {
            throw new UnsupportedUserException(sprintf('Invalid user class "%s".', get_class($user)));
        }

        throw new Exception('TODO: fill in refreshUser() inside '.__FILE__);
    }

    public function supportsClass($class)
    {
        return DummyUser::class === $class;
    }
}
