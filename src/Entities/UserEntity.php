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
            // echo "<script> var is_admin = " . json_encode(true) . "</script>";
            return true;
        } else {
            // echo "<script> var is_admin = " . json_encode(false) . "</script>";
            return false;
        }
    }
}
