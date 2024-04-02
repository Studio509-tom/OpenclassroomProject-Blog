<?php

namespace Application\Controllers;

use Application\Model\CommentModel;
use Application\ParentController;
use Application\Controllers\c;
class CommentController extends ParentController
{
    /**
     * addComment
     *
     * @param  mixed $session_user
     * @param  string $id_article
     * @return void
     */
    public function addComment(mixed $session_user, string $id_article): void
    {
        $input = $_POST;
        $user = null;
        if ($session_user !== null) {
            $user = $session_user;
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
                    $articleController->articlePage($session_user,$id_article,null,$message);
                }
            }
        } else {
            header('Location: index.php?action=article&id-article=' . $id_article);
        }
    }
    /**
     * modifyComment
     *
     * @param  mixed $session_user
     * @param  string $id_comment
     * @param  string $id_article
     * @return void
     */
    public function modifyComment(mixed $session_user, string $id_comment, string $id_article): void
    {
        $success = false;
        $input = $_POST;
        $user = null;
        if ($session_user !== null) {
            $user = $session_user;
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
     * @param  mixed $session_user
     * @param  string $id_comment
     * @param  string $id_article
     * @return void
     */
    public function deleteComment(mixed $session_user, string $id_comment, string $id_article): void
    {
        $success = false;
        $input = $_POST;
        $user = null;
        if ($session_user !== null) {
            $user = $session_user;
        }
        $commentModel = new CommentModel();
        $comment = $commentModel->getComment($id_comment);
        if ($user->id == $comment->user->id || $user->admin) {
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
     * @param  mixed $session_user
     * @param  string $id_comment
     * @param  string $id_article
     * @return void
     */
    public function valideComment(mixed $session_user, string $id_comment, string $id_article): void
    {
        $success = false;
        $user = null;
        if ($session_user !== null) {
            $user = $session_user;
        }
        if ($user !== null) {
            if ($user->admin) {
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
