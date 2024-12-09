<?php

namespace Application\Controllers;

use Application\Model\CommentModel;
use Application\Model\UserModel;
use Application\ParentController;
use Application\Controllers\c;

class CommentController extends ParentController
{
    /**
     * Ajouter un commentaire 
     *
     * @param  string $id_article
     * @return void
     */
    public function addComment(string $id_article): void
    {
        $input = $_POST;
        $user = null;
        $connect = false;
        if (isset($_SESSION['user'])) {
            $user = $_SESSION['user'];
            $connect = true;
        }
        // Vérifier si l'utilisateur est connecter 
        if ($user !== null) {
            // Vérifier que le champs ne soit pas vide 
            if (!empty($input["comment"])) {
                $content = htmlspecialchars($input["comment"]);
                $commentModel = new CommentModel();
                // Ajouter le commentaire 
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
     * Modifier un commentaire 
     *
     * @param  string $id_comment
     * @param  string $id_article
     * @return void
     */
    public function modifyComment(string $id_comment, string $id_article): void
    {
        $success = false;
        $input = $_POST;
        $user = null;
        $connect = false;
        if (isset($_SESSION['user'])) {
            $user = $_SESSION['user'];
            $connect = true;
        }
        // Récupération du commentaire 
        $commentModel = new CommentModel();
        $comment = $commentModel->getComment($id_comment);
        // Si l'utilisateur est le créateur du commentaire
        if ($user->id == $comment->user->id) {
            // Si le champs n'est pas vide 
            if (!empty($input["comment-modify"])) {
                $content = htmlspecialchars($input["comment-modify"]);
                $commentModel = new CommentModel();
                // Modification du commentaire 
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
     * Suppression d'un commentaire
     *
     * @param  string $id_comment
     * @param  string $id_article
     * @return void
     */
    public function deleteComment(string $id_comment, string $id_article): void
    {
        $success = false;
        $input = $_POST;
        $user = null;
        $connect = false;
        if (isset($_SESSION['user'])) {
            $user = $_SESSION['user'];
            $connect = true;
        }
        // Récupération du commentaire 
        $commentModel = new CommentModel();
        $comment = $commentModel->getComment($id_comment);
        $userModel = new UserModel();
        // Si l'utilisateur est le créateur ou un admin 
        if ($user->id == $comment->user->id || $user->isAdmin()) {
            $commentModel = new CommentModel();
            // Suppression du commentaire 
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
     * validation du commentaire
     *
     * @param  string $id_comment
     * @param  string $id_article
     * @return void
     */
    public function valideComment(string $id_comment, string $id_article): void
    {
        $success = false;
        $user = null;
        $connect = false;
        if (isset($_SESSION['user'])) {
            $user = $_SESSION['user'];
            $connect = true;
        }
        
        if ($user !== null) {
            $userModel = new UserModel();
            // Si l'utilisateur est administrateur 
            if ($user->isAdmin()) {
                $commentModel = new CommentModel();
                // Validation du commentaire 
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
