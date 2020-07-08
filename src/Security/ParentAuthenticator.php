<?php

namespace App\Security;

use Doctrine\DBAL\Connection;

class ParentAuthenticator {
    private $connection;

    public function __construct(Connection $connection) {
        $this->connection = $connection;
    }

    public function authenticate(string $username, string $password): User {
        $stmt = $this->connection->prepare('SELECT * FROM students WHERE parent_username = :username LIMIT 1');
        $stmt->bindParam('username', $username);

        $stmt->execute();
        $user = $stmt->fetch();

        if($user === false) {
            throw new AuthenticationException();
        }

        dump($user);

        if(password_verify($password, $user['parent_password']) !== true) {
            throw new AuthenticationException();
        }

        return (new User())
            ->setUsername($username)
            ->setFirstname($user['firstname'])
            ->setLastname($user['lastname']);
    }
}