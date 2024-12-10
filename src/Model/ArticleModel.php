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
     * Ajout de l'article 
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
        $statement = $this->connection->getConnection()->prepare(
            'INSERT INTO articles( title, chapo , content , author, date_creation) VALUES(?, ?, ?, ?, ?)'
        );
        $affectedLines = $statement->execute([$title, $chapo, $content, $author, $date]);
        return ($affectedLines > 0);
    }

    /**
     * Modification de l'article 
     *
     * @param  string $title
     * @param  string $chapo
     * @param  string $content
     * @param  string $date
     * @param  string $author
     * @param  string $id
     * @return bool
     */
    public function modifyArticle(string $title, string $chapo, string $content, string $date,string $author ,string $id_article): bool
    {
        
        $statement = $this->connection->getConnection()->prepare(
            'UPDATE articles SET title = ?, chapo = ?, content = ?,author = ?, date_creation = ? WHERE id = ?'
        );
        $affectedLines = $statement->execute([$title, $chapo, $content,$author, $date , $id_article]);

        return ($affectedLines > 0);
    }

    /**
     * Suppression de l'article
     *
     * @param  mixed $id
     * @return bool
     */
    public function deleteArticle(string $id_article): bool
    {
        $statement = $this->connection->getConnection()->prepare(
            'DELETE FROM articles WHERE id = ?'

        );
        $affectedLines = $statement->execute([$id_article]);

        return ($affectedLines > 0);
    }


    /**
     * Récupération des articles
     *
     * @return array
     */
    public function getArticles(): array
    {

        $statement = $this->connection->getConnection()->query(
            "SELECT articles.*, 
            DATE_FORMAT(articles.date_creation, '%d/%m/%Y %H:%i:%s') AS date_creation,
            users.user_id, users.name, users.firstname, users.email
            FROM articles
            LEFT JOIN users 
            ON articles.author = users.user_id
            ORDER BY articles.id DESC;"
        );
        $articles = [];
        while (($row = $statement->fetch())) {
            $article = new ArticleEntity();
            $article->id = $row['id'];
            $article->title = $row['title'];
            $article->chapo = $row['chapo'];
            $article->content = $row['content'];
            $article->date = $row['date_creation'];

            if ($row['user_id'] !== null) {
                $user = new UserEntity;
                $user->id = $row['user_id'];
                $user->name = $row['name'];
                $user->firstname = $row['firstname'];
                $user->email = $row['email'];
                $article->author = $user;
            }

            $articles[$article->id] = $article;
        }
        return $articles;
    }
    /**
     * Récupération d'un article
     *
     * @param  string $id
     * @return mixed
     */
    public function getArticle(string $id_article): mixed
    {
        $statement = $this->connection->getConnection()->prepare(
            "SELECT * , DATE_FORMAT(date_creation, '%d/%m/%Y %H:%i:%s') AS date_creation
            FROM articles 
            LEFT JOIN users ON (author = user_id)
            WHERE id = ?
            "

        );
        $statement->execute([$id_article]);
        //Utilisation du fetch pour voir chacune des donné de notre tableau
        $row = $statement->fetch();
        if ($row === false) {
            return null;
        }
        $article = new ArticleEntity();
        $article->id = $row['id'];
        $article->title = $row['title'];
        $article->chapo = $row['chapo'];
        $article->content = $row['content'];
        $article->date = $row['date_creation'];

        if ($row['user_id'] !== null) {
            $user = new UserEntity;
            $user->id = $row['user_id'];
            $user->name = $row['name'];
            $user->firstname = $row['firstname'];
            $user->email = $row['email'];
            $article->author = $user;
        }

        return $article;
    }
}
