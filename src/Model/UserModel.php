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
     * @param  string $id_user
     * @return mixed 
     */
    public function getUser(string $id_user) : mixed
    {
        $statement = $this->connection->getConnection()->prepare(
            'SELECT * FROM users WHERE user_id = ?'
        );
        $statement->execute([$id_user]);
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
        $user->role = $row['role'];
        $user->password = $row['password'];


        return $user;
    }    
    /**
     * Vérification d'un utilisateur
     *
     * @param  string $email
     * @return mixed
     */
    public function checkedUser(string $email): mixed
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
        $user->role = $row['role'];
        $user->password = $row['password'];


        return $user;
    }
    /**
     * Récupération de tout les utilisateurs
     *
     * @return array
     */
    public function getUsers(): array
    {
        $statement = $this->connection->getConnection()->query(
            "SELECT * FROM users ORDER BY firstname, name"
        );
        $users = [];
        while (($row = $statement->fetch())) {
            $user = new UserEntity();
            $user->id = $row['user_id'];
            $user->name = $row['name'];
            $user->firstname = $row['firstname'];
            $user->email = $row['email'];
            $user->role = $row['role'];

            $users[$user->id] = $user;
        }
        return $users;
    }

    /**
     * Modification du role 
     *
     * @param  string $user_id
     * @param  string $new_value_admin
     * @return bool
     */
    public function modifyRole(string $user_id, string $new_value_admin): bool
    {
        $statement = $this->connection->getConnection()->prepare(
            "UPDATE users
            SET role = ?
            WHERE user_id = ?;"
        );

        $affectedLines = $statement->execute([$new_value_admin, $user_id]);
        return ($affectedLines > 0);
    }

    /**
     * Suppression de l'utilisateur
     *
     * @param  string $id_user
     * @return bool
     */
    public function deleteUser(string $id_user): bool
    {
        $statement = $this->connection->getConnection()->prepare(
            "DELETE FROM users
            WHERE user_id = ?;"
        );

        $affectedLines = $statement->execute([$id_user]);

        return ($affectedLines > 0);
    }

    /**
     * Modification du mots de passe
     *
     * @param  string $new_password
     * @param  string $email_user
     * @return bool
     */
    public function modifyPassword(string $new_password, string $email_user): bool
    {
        $statement = $this->connection->getConnection()->prepare(
            "UPDATE users
            SET password = ?
            WHERE email = ?;"
        );

        $affectedLines = $statement->execute([$new_password, $email_user]);
        return ($affectedLines > 0);
    }

    /**
     * Vérification si admin 
     *
     * @return mixed
     */
    public function checkAdmin(): mixed
    {
        $statement = $this->connection->getConnection()->query(
            "SELECT COUNT(*) AS number_admin
            FROM users
            WHERE role = 'admin'"
        );
        return $statement->fetch();
    }
    
    
}
