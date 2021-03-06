<?php

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;

class UserRepository extends EntityRepository implements UserProviderInterface
{
    /**
     * Vraća aktivnog korisnika na temelju korisničkog imena.
     *
     * Korisničko ime u slučaju ove aplikacije je korisnikov email.
     *
     * @param  $username
     * @return \App\Bundle\User
     * @throws UsernameNotFoundException
     */
    public function loadUserByUsername($username)
    {
        $user = $this->findOneBy(
            [
                'email' => $username,
                'isActive' => true
            ]
        );

        if (!$user) {
            throw new UsernameNotFoundException(sprintf('Username "%s" does not exist.', $username));
        }

        return $user;
    }

    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', get_class($user)));
        }

        return $this->loadUserByUsername($user->getUsername());
    }

    public function supportsClass($class)
    {
        return $class === 'AppBundle\Entity\User';
    }
}
