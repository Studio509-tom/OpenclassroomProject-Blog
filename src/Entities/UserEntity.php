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
     * isAdmin
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
