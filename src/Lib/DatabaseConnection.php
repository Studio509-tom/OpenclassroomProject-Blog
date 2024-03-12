<?php
namespace Application\Lib;
class DatabaseConnection
{
    public ?\PDO $database = null;

    public function getConnection(): \PDO
    {
        if ($this->database === null) {
            $this->database = new \PDO('mysql:host=localhost;dbname=blog_ocr_db;charset=utf8', 'root', '');
        }
        return $this->database;
    }
}
