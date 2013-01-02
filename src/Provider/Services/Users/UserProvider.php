<?php

namespace Provider\Services\Users;

use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Provider\Services\Users\User;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Doctrine\DBAL\Connection;

class UserProvider implements UserProviderInterface {

    private $conn;

    public function __construct(Connection $conn) {
        $this->conn = $conn;
    }

    public function loadUserByUsername($username) {
        $sql = "SELECT c . * , 
                GROUP_CONCAT( role_name SEPARATOR ',' ) AS roles
                FROM users c
                LEFT JOIN user_roles crl ON crl.user_id = c.id
                AND crl.role_id
                IN ( 1, 2 )
                LEFT JOIN roles r ON r.id = crl.role_id
                WHERE c.username = ?
                GROUP BY c.id
                LIMIT 1";
        $stmt = $this->conn->executeQuery($sql,array(strtolower($username)));
        if (!$user = $stmt->fetch()) {
            throw new UsernameNotFoundException(sprintf('Username "%s" does not exist.', $username));
        }
        return new User($user['id'],$user['username'], $user['password'],explode(',', $user['roles']), true, true, true, true);
    }

    public function refreshUser(UserInterface $user) {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', get_class($user)));
        }

        return $this->loadUserByUsername($user->getUsername());
    }

    public function supportsClass($class) {
        return $class === 'Symfony\Component\Security\Core\User\User';
    }

}