<?php

namespace Application\Entities;


class UserEntity
{
    public string $id;
    public string $name;
    public string $firstname;
    public string $email;
    public $password;
    public $role;

    /**
     * Vérifier si l'user à le rôle admin
     *
     * @return bool
     */
    public function isAdmin(): bool
    {
        if ($this->role == "admin") {
            return true;
        } else {
            return false;
        }
    }
}
