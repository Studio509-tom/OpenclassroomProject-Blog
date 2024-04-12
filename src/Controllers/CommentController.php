<?php

namespace Application\Controllers;

use Application\Model\CommentModel;
use Application\Model\UserModel;
use Application\ParentController;
use Application\Controllers\c;

class CommentController extends ParentController
{
    /**
     * addComment
     *
     * @param  string $id_article
     * @return void
     */
    public function addComment(string $id_article): void
    {
        global $session_user;
        $input = $_POST;
        $user = null;
        $connect = false;
        if ($session_user !== null) {
            $user = $session_user;
            $connect = true;
        }
        
        if ($user !== null) {
            if (!empty($input["comment"])) {
                $content = htmlspecialchars($input["comment"]);
                $commentModel = new CommentModel();
                $success = $commentModel->addComment($content, $user->id, $id_article);
                $message = "Votre commentaire sera visible dès qu'il aura était vérifier";
                if (!$success) {
                    throw new \Exception('Une erreur est surevenu');
                } else {
                    $articleController = new ArticleController();
                    $articleController->articlePage($id_article, null, $message);
                }
            }
        } else {
            header('Location: index.php?action=article&id-article=' . $id_article);
        }
    }
    /**
     * modifyComment
     *
     * @param  string $id_comment
     * @param  string $id_article
     * @return void
     */
    public function modifyComment(string $id_comment, string $id_article): void
    {
        global $session_user;
        $success = false;
        $input = $_POST;
        $user = null;
        $connect = false;
        if ($session_user !== null) {
            $user = $session_user;
            $connect = true;
        }
        
        $commentModel = new CommentModel();
        $comment = $commentModel->getComment($id_comment);
        if ($user->id == $comment->user->id) {
            if (!empty($input["comment-modify"])) {
                $content = htmlspecialchars($input["comment-modify"]);
                $commentModel = new CommentModel();
                $success = $commentModel->modifyComment($content, $id_comment);
            }
            if (!$success) {
                throw new \Exception('Une erreur est surevenu');
            } else {

                header('Location: index.php?action=article&id-article=' . $id_article);
            }
        } else {
            throw new \Exception("Vous n'êtes pas autorisé à effectuer cette action");
        }
    }

    /**
     * deleteComment
     *
     * @param  string $id_comment
     * @param  string $id_article
     * @return void
     */
    public function deleteComment(string $id_comment, string $id_article): void
    {
        global $session_user;
        $success = false;
        $input = $_POST;
        $user = null;
        $connect = false;
        if ($session_user !== null) {
            $user = $session_user;
            $connect = true;
        }
        
        $commentModel = new CommentModel();
        $comment = $commentModel->getComment($id_comment);
        $userModel = new UserModel();
        if ($user->id == $comment->user->id || $user->isAdmin()) {
            $commentModel = new CommentModel();
            $success = $commentModel->deleteComment($id_comment);
            if (!$success) {
                throw new \Exception('Une erreur est surevenu');
            } else {
                header('Location: index.php?action=article&id-article=' . $id_article);
            }
        } else {
            throw new \Exception("Vous n'êtes pas autorisé à effectuer cette action");
        }
    }

    /**
     * valideComment
     *
     * @param  string $id_comment
     * @param  string $id_article
     * @return void
     */
    public function valideComment(string $id_comment, string $id_article): void
    {
        global $session_user;
        $success = false;
        $user = null;
        $user = null;
        $connect = false;
        if ($session_user !== null) {
            $user = $session_user;
            $connect = true;
        }
        
        if ($user !== null) {
            $userModel = new UserModel();
            if ($user->isAdmin()) {
                $commentModel = new CommentModel();
                $success = $commentModel->valideComment($id_comment);
                if (!$success) {
                    throw new \Exception('Une erreur est surevenu');
                } else {
                    header('Location: index.php?action=article&id-article=' . $id_article);
                }
            } else {
                throw new \Exception("Vous n'êtes pas autorisé à effectuer cette action");
            }
        } else {
            throw new \Exception("Vous n'êtes pas autorisé à effectuer cette action");
        }
    }
}
