<?php

namespace Application\Model;

use Application\Lib\DatabaseConnection;
use Application\Entities\UserEntity;

class UserModel {
    public DatabaseConnection $connection;
    public function __construct(){
        $this->connection = new DatabaseConnection();
    }
    public function addUser($name , $firstname , $email , $password_hash){
        $statement = $this->connection->getConnection()->prepare(
            'INSERT INTO users( name, firstname , email , password) VALUES(?, ?, ?, ?)'
        );
        $affectedLines = $statement->execute([$name, $firstname, $email , $password_hash]);
        return ($affectedLines > 0);
    }
}