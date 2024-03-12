<?php

namespace Application\Model;

use Application\Lib\DatabaseConnection;
use Application\Entities\UserEntity;

class UserModel {
    public DatabaseConnection $connection;
    public function __construct(){
        $this->connection = new DatabaseConnection();
    }
    public function addUser($name , $firstname , $email , $password_hash ){
        $statement = $this->connection->getConnection()->prepare(
            'INSERT INTO users( name, firstname , email , password) VALUES(?, ?, ?, ?)'
        );
        $affectedLines = $statement->execute([$name, $firstname, $email , $password_hash]);
        return ($affectedLines > 0);
    }
    public function getUser($email){
        $statement = $this->connection->getConnection()->prepare(
            'SELECT * FROM users WHERE email = ?'
        );
        $statement->execute([$email]);
        //Utilisation du fetch pour voir chacune des donné de notre tableau
        $row = $statement->fetch();
        if($row === false) {
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
    public function login(){

    }
    
}