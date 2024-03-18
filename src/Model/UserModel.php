<?php

namespace Application\Model;

use Application\Lib\DatabaseConnection;
use Application\Entities\UserEntity;

class UserModel
{
    public DatabaseConnection $connection;    
    /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
        $this->connection = new DatabaseConnection();
    }

    /**
     * Ajout d'un utilisateur en base de donnée
     *
     * @param  string $name
     * @param  string $firstname
     * @param  string $email
     * @param  string $password_hash
     * @return bool
     */
    public function addUser(string $name, string $firstname, string $email, string $password_hash)
    {
        $statement = $this->connection->getConnection()->prepare(
            'INSERT INTO users( name, firstname , email , password) VALUES(?, ?, ?, ?)'
        );
        $affectedLines = $statement->execute([$name, $firstname, $email, $password_hash]);
        return ($affectedLines > 0);
    }

    /**
     * Récupère l'utilisateur en base de donnée
     *
     * @param  string $email
     * @return obj or null
     */
    public function getUser($email)
    {
        $statement = $this->connection->getConnection()->prepare(
            'SELECT * FROM users WHERE email = ?'
        );
        $statement->execute([$email]);
        //Utilisation du fetch pour voir chacune des donné de notre tableau
        $row = $statement->fetch();
        if ($row === false) {
            return null;
        }
        $user = new UserEntity;
        $user->id = $row['user_id'];
        $user->name = $row['name'];
        $user->firstname = $row['firstname'];
        $user->email = $row['email'];
        $user->password = $row['password'];

        return $user;
    }
    
}
