<?php

namespace Application\Model;

use Application\Lib\DatabaseConnection;
use Application\ParentController;
use Application\Entities\ArticleEntity;
use Application\Entities\UserEntity;
use Application\Entities\CommentEntity;


class CommentModel extends ParentController
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
     * Ajout d'un commentaire
     *
     * @param  string $content
     * @param  string $user_id
     * @param  string $id
     * @return bool
     */
    public function addComment(string $content, string $user_id, string $id_article): bool
    {
        $statement = $this->connection->getConnection()->prepare(
            'INSERT INTO comments(content_comment , user_id , validate, article_id ) VALUES(?, ?, ?, ?)'
        );
        $affectedLines = $statement->execute([$content, $user_id, false, $id_article]);
        return ($affectedLines > 0);
    }

    /**
     * Récupération des commentaires
     *
     * @param  string $id
     * @return array
     */
    public function getComments(string $id_article): array
    {
        $statement = $this->connection->getConnection()->prepare(
            "SELECT * FROM comments
            JOIN  articles ON (comments.article_id = articles.id)
            JOIN users ON (comments.user_id = users.user_id)
            WHERE id = ?
            "
        );
        $statement->execute([$id_article]);

        $comments = [];

        while (($row = $statement->fetch())) {
            $user = new UserEntity();
            $user->id = $row['user_id'];
            $user->name = $row['name'];
            $user->firstname = $row['firstname'];
            $user->email = $row['email'];

            $article = new ArticleEntity();
            $article->id = $row['id'];
            $article->title = $row['title'];
            $article->chapo = $row['chapo'];
            $article->content = $row['content'];
            $article->date = $row['date_creation'];

            $comment = new CommentEntity();
            $comment->id = $row['id_comment'];
            $comment->content_comment = $row['content_comment'];
            $comment->validate = $row['validate'];
            $comment->article = $article;
            $comment->user = $user;


            $comments[$comment->id] = $comment;
        }
        return $comments;
    }

    /**
     * Récupération d'un commentaire
     *
     * @param  string $id_comment
     * @return mixed
     */
    public function getComment(string $id_comment)
    {
        $statement = $this->connection->getConnection()->prepare(
            "SELECT * FROM comments 
            JOIN  articles ON (comments.article_id = articles.id)
            JOIN users ON (comments.user_id = users.user_id)
            WHERE id_comment = ?
            "
        );
        $statement->execute([$id_comment]);
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
        $article->date = $row['date_creation'];

        $comment = new CommentEntity();
        $comment->id = $row['id_comment'];
        $comment->content_comment = $row['content_comment'];
        $comment->user = $user;
        $comment->validate = $row['validate'];
        $comment->article = $article;

        return $comment;
    }

    /**
     * Modification d'un commentaire
     *
     * @param  string $content
     * @param  string $id_comment
     * @return bool
     */
    public function modifyComment(string $content, string $id_comment): bool
    {
        $statement = $this->connection->getConnection()->prepare(
            "UPDATE comments SET content_comment = ? WHERE id_comment = ?"
        );
        $affectedLines = $statement->execute([$content, $id_comment]);
        return ($affectedLines > 0);
    }

    /**
     * Suppression d'un commentaire
     *
     * @param  string $id_comment
     * @return bool
     */
    public function deleteComment(string $id_comment): bool
    {
        $statement = $this->connection->getConnection()->prepare(
            "DELETE FROM comments WHERE id_comment = ?;"
        );
        $affectedLines = $statement->execute([$id_comment]);
        return ($affectedLines > 0);
    }

    /**
     * Suppression des commentaires
     *
     * @param  string $id_article
     * @return bool
     */
    public function deleteComments(string $id_article): bool
    {
        $statement = $this->connection->getConnection()->prepare(
            "DELETE FROM comments WHERE article_id = ?;"
        );
        $affectedLines = $statement->execute([$id_article]);
        return ($affectedLines > 0);
    }

    /**
     * Validation d'un commentaire
     *
     * @param  string $id_comment
     * @return bool
     */
    public function valideComment(string $id_comment): bool
    {
        $statement = $this->connection->getConnection()->prepare(
            "UPDATE comments SET validate = '1' WHERE id_comment = ?;"
        );

        $affectedLines = $statement->execute([$id_comment]);
        return ($affectedLines > 0);
    }
}
