<?php

namespace Application\Model;

use Application\Lib\DatabaseConnection;
use Application\Entities\ArticleEntity;
use Application\Entities\UserEntity;

class ArticleModel
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
     * addArticle
     *
     * @param  string $title
     * @param  string $chapo
     * @param  string $content
     * @param  int $author
     * @param  string $date
     * @return bool
     */
    public function addArticle(string $title, string $chapo, string $content, int $author, string $date): bool
    {
        var_dump($title, $chapo, $content, $author, $date);
        $statement = $this->connection->getConnection()->prepare(
            'INSERT INTO articles( title, chapo , content , author, date_creation) VALUES(?, ?, ?, ?, ?)'
        );
        $affectedLines = $statement->execute([$title, $chapo, $content, $author, $date]);
        return ($affectedLines > 0);
    }
    
    /**
     * modifyArticle
     *
     * @param  string $title
     * @param  string $chapo
     * @param  string $content
     * @param  string $date
     * @param  string $id
     * @return bool
     */
    public function modifyArticle(string $title,string $chapo,string $content ,string $date ,string $id): bool
    {
        $statement = $this->connection->getConnection()->prepare(
            'UPDATE articles SET title = ?, chapo = ?, content = ?, date_creation = ? WHERE id = ?'
        );
        $affectedLines = $statement->execute([$title, $chapo, $content, $date , $id]);

        return ($affectedLines > 0);
        
    }

    /**
     * getArticles
     *
     * @return array
     */
    public function getArticles(): array
    {

        $statement = $this->connection->getConnection()->query(
            "SELECT * , DATE_FORMAT(date_creation, '%d/%m/%Y %H:%i:%s') AS date_creation
            FROM articles 
            JOIN users ON (author = user_id) 
            ORDER BY id DESC"
        );
        $articles = [];
        $users = [];
        while (($row = $statement->fetch())) {
            $user = new UserEntity;
            $user->id = $row['user_id'];
            $user->name = $row['name'];
            $user->firstname = $row['firstname'];
            $user->email = $row['email'];

            $article = new ArticleEntity();
            $article->id = $row['id'];
            $article->title = $row['title'];
            $article->chapo = $row['chapo'];
            $article->content = $row['content'];
            $article->author = $user;
            $article->date = $row['date_creation'];

            $articles[$article->id] = $article;
        }
        return $articles;
    }    
    /**
     * getArticle
     *
     * @param  string $id
     * @return object
     */
    public function getArticle(string $id):object
    {
        $statement = $this->connection->getConnection()->prepare(
            "SELECT * , DATE_FORMAT(date_creation, '%d/%m/%Y %H:%i:%s') AS date_creation
            FROM articles 
            JOIN users ON (author = user_id)
            WHERE id = ?
            "
            
        );
        var_dump($id);
        $statement->execute([$id]);
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

        $article = new ArticleEntity();
        $article->id = $row['id'];
        $article->title = $row['title'];
        $article->chapo = $row['chapo'];
        $article->content = $row['content'];
        $article->author = $user;
        $article->date = $row['date_creation'];

        return $article;
    }

}
