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
     * @return mixed 
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
        $user->role = $row['role'];
        $user->password = $row['password'];


        return $user;
    }
    /**
     * getUsers
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
     * modifyRole
     *
     * @param  string $email_user
     * @param  string $new_value_admin
     * @return bool
     */
    public function modifyRole(string $email_user, string $new_value_admin): bool
    {
        $statement = $this->connection->getConnection()->prepare(
            "UPDATE users
            SET role = ?
            WHERE email = ?;"
        );

        $affectedLines = $statement->execute([$new_value_admin, $email_user]);
        return ($affectedLines > 0);
    }

    /**
     * deleteUser
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
     * modifyPassword
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
     * checkAdmin
     *
     * @return mixed
     */
    public function checkAdmin(): mixed
    {
        $statement = $this->connection->getConnection()->query(
            "SELECT COUNT(*) AS numbre_admin
            FROM users
            WHERE role = 'admin';"
        );
        return $statement->fetch();
    }

    public function isAdmin(string $id_user)
    {
        $statement = $this->connection->getConnection()->prepare(
            "SELECT * FROM users
            WHERE user_id = ?
            AND role = 'admin';"
        );
        $affectedLines = $statement->execute([$id_user]);
        var_dump($affectedLines);

        return $affectedLines;
    }
}
