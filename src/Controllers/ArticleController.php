<?php

namespace Application\Controllers;

use Application\Model\UserModel;
use Application\ParentController;
use Application\Model\ArticleModel;
use Application\Model\CommentModel;
use Exception;

class ArticleController extends ParentController
{
    /**
     * Page de l'ajout d'article 
     *
     * @return void
     */
    public function addArticlePage(): void
    {
        $user = null;
        $connect = false;
        if (isset($_SESSION['user'])) {
            $user = $_SESSION['user'];
            $connect = true;
        }

        $userModel = new UserModel();
        $users = $userModel->getUsers();
        echo $this->twig->render("create-article.html.twig", ['title' => "Article", "users" => $users, 'user' => $user, 'connect' => $connect]);
    }


    /**
     * Page des articles 
     * @return void
     */
    public function articlesPage(): void
    {
        $user = null;
        $connect = false;
        if (isset($_SESSION['user'])) {
            $user = $_SESSION['user'];
            $connect = true;
        }
        // Récupération de tout les articles 
        $articleModel = new ArticleModel();
        $articles = $articleModel->getArticles();

        echo $this->twig->render("articles.html.twig", ['title' => "Article", 'user' => $user, 'connect' => $connect, 'articles' => $articles]);
    }

    /**
     * Modification de l'article 
     *
     * @param  string $id_article
     * @return void
     */
    public function modifyArticle(string $id_article): void
    {
        $input = $_POST;
        $user = null;
        $connect = false;
        if (isset($_SESSION['user'])) {
            $user = $_SESSION['user'];
            $connect = true;
        }
        // Récupération de l'article 
        $articleModel = new ArticleModel();
        $article = $articleModel->getArticle($id_article);
        // Vérification si l'utilisateur est connecter
        if ($connect && ($input !== null)) {
            // Vérification que les champs ne soit pas vide 
            if (!empty($input["input-title"]) && !empty($input["input-chapo"])  && !empty($input["input-content"])) {
                // Vérification que l'utilisateur est l'autheur ou un admin 
                if ((isset($article->author) && $user->id == $article->author->id) || $user->isAdmin()) {

                    $title = htmlspecialchars($input["input-title"]);
                    $chapo = htmlspecialchars($input["input-chapo"]);
                    $content = htmlspecialchars($input["input-content"]);
                    $author = $input["select-author"];
                    if ($author == "") {
                        throw new Exception('Une erreur est surevenu');
                    }
                    // Récupération de la date du jour 
                    $date_time_zone = new \DateTimeZone('Europe/Paris');
                    $date = new \DateTime('now', $date_time_zone);
                    // Récupération de l'autheur 
                    $userModel = new UserModel();
                    $user_exist = $userModel->getUser($author);
                    // Vérification que l'autheur à était récupéré 
                    if (!is_null($user_exist)) {
                        // Modification de l'article 
                        $articleModel = new ArticleModel();
                        $success = $articleModel->modifyArticle($title, $chapo, $content, $date->format("Y/m/d H:i:s"), $author, $id_article);
                        if (!$success) {
                            throw new Exception('Une erreur est surevenu');
                        } else {

                            header('Location: index.php?action=article&id-article=' . $id_article);
                        }
                    } else {
                        throw new Exception("L'utilisateur séléctionner n'éxiste pas");
                    }
                }
            } else {
                $userModel = new UserModel();
                $users = $userModel->getUsers();
                $article->title = htmlspecialchars_decode($article->title);
                $article->chapo = htmlspecialchars_decode($article->chapo);
                $article->content = htmlspecialchars_decode($article->content);
                echo $this->twig->render("modification-article.html.twig", ['title' => "Article", 'user' => $user, "users" => $users, 'connect' => $connect, "article" => $article, "titleArticle" => $input["input-title"], "chapo" => $input["input-chapo"], "content" => $input["input-content"], 'error' => true]);
            }
        }
    }

    /**
     * Suppression de l'article 
     *
     * @param  string $id_article
     * @return void
     */
    public function deleteArticle(string $id_article): void
    {
        $user = null;
        $connect = false;
        if (isset($_SESSION['user'])) {
            $user = $_SESSION['user'];
            $connect = true;
        }
        // récupération de l'article 
        $articleModel = new ArticleModel();
        $article = $articleModel->getArticle($id_article);
        $userModel = new UserModel();
        // Si l'utilisateur est l'autheur ou un admin 
        if ($user->id == $article->author->id || $user->isAdmin()) {
            // Suppression de l'article 
            $article_model = new ArticleModel();
            $success_article = $article_model->deleteArticle($id_article);
            // Suppression des commentaire associé 
            $commentModel = new CommentModel();
            $success_comment = $commentModel->deleteComment($id_article);
            if (!$success_article && !$success_comment) {
                throw new Exception('Une erreur est surevenu');
            } else {
                header('Location: index.php?action=articles');
            }
        }
    }

    /**
     * Page de l'article 
     *
     * @param  string $id_article
     * @return void
     */
    public function articlePage(string $id_article, mixed $id_comment, string $message): void
    {
        $user = null;
        $connect = false;
        if (isset($_SESSION['user'])) {
            $user = $_SESSION['user'];
            $connect = true;
        }
        // Récupérationde l'article 
        $articleModel = new ArticleModel();
        $article = $articleModel->getArticle($id_article);
        $article->content = html_entity_decode($article->content);
        // Récupération des commentaires de l'article 
        $commentModel = new CommentModel();
        $comments = $commentModel->getComments($id_article);
        if ($id_comment !== null) {
            if ($user->id !== $comments[$id_comment]->user->id) {
                $id_comment = null;
            }
        }
        echo $this->twig->render("article.html.twig", ['title' => "Article", 'modifyState' => $id_comment, 'comments' => $comments, 'user' => $user, 'connect' => $connect, 'article' => $article, 'message' => $message]);
    }



    /**
     * Page de modification de l'article 
     *
     * @param  string $id
     * @return void
     */
    public function modifyPage(string $id_article): void
    {
        $user = null;
        $connect = false;
        if (isset($_SESSION['user'])) {
            $user = $_SESSION['user'];
            $connect = true;
        }
        // récupération de l'article
        $articleModel = new ArticleModel();
        $article = $articleModel->getArticle($id_article);
        $article->content = html_entity_decode($article->content);
        // Récupéré les utilisateurs 
        $userModel = new UserModel();
        $users = $userModel->getUsers();

        echo $this->twig->render("modification-article.html.twig", ['title' => "Article", 'users' => $users, 'user' => $user, 'connect' => $connect, 'article' => $article]);
    }


    /**
     * Ajout de l'article 
     *
     * @return void
     */
    public function addArticle(): void
    {
        $input = $_POST;
        $user = null;
        $connect = false;
        if (isset($_SESSION['user'])) {
            $user = $_SESSION['user'];
            $connect = true;
        }
        // Si connecter 
        if ($connect && ($input !== null)) {
            // Vérifier que les champs ne sois pas vide 
            if (!empty($input["input-title"]) && !empty($input["input-chapo"])  && !empty($input["input-content"])) {
                // Vérifier le contenu des champs 
                if ((!preg_match("/^[a-zA-Z0-9 ]*$/", $input["input-title"]) || !preg_match("/^[a-zA-Z0-9 ]*$/", $input["input-chapo"]))) {
                    echo $this->twig->render("create-article.html.twig", ['title' => "Article",  'connect' => $connect, "user" => $user, "titleArticle" => $input["input-title"], "chapo" => $input["input-chapo"], "content" => $input["input-content"], 'error' => true]);
                } else {
                    // Récupération des inputs 
                    $title = $input["input-title"];
                    $chapo = $input["input-chapo"];
                    $content = htmlspecialchars($input["input-content"]);
                    // Vérification si l'utilisateur est admin 
                    var_dump($input["select-author"]);
                    if ($user->isAdmin()) {
                        $author = $input["select-author"];
                        if ($author == '') {
                            throw new Exception('Une erreur est surevenu');
                        }
                    } else {
                        $author = $user->id;
                    }
                   
                    // Récupération de la date d'ajourd'hui
                    $date_time_zone = new \DateTimeZone('Europe/Paris');
                    $date = new \DateTime('now', $date_time_zone);
                    // Récupération de l'autheur
                    $userModel = new UserModel();
                    $user_exist = $userModel->getUser($author);
                   
                    if (!is_null($user_exist)) {
                        // Ajouter l'article
                        $articleModel = new ArticleModel();
                        $success = $articleModel->addArticle($title, $chapo, $content, $author, $date->format("Y/m/d H:i:s"));
                        if (!$success) {
                            throw new Exception('Une erreur est surevenu');
                        } else {
                            header('Location: index.php?action=articles');
                        }
                    } else {
                        throw new Exception("L'ustilisateur séléctionner n'éxiste pas.");
                    }
                }
            } else {
                echo $this->twig->render("create-article.html.twig", ['title' => "Article",  'connect' => $connect, "user" => $user, "titleArticle" => $input["input-title"], "chapo" => $input["input-chapo"], "content" => $input["input-content"], 'error' => true]);
            }
        } else {
            echo $this->twig->render("create-article.html.twig", ['title' => "Article",  'connect' => $connect, "user" => $user, "titleArticle" => $input["input-title"], "chapo" => $input["input-chapo"], "content" => $input["input-content"], 'error' => true]);
        }
    }

    /**
     * Gestions des articles 
     *
     * @return void
     */
    public function managementArticles(): void
    {
        $user = null;
        $connect = false;
        if (isset($_SESSION['user'])) {
            $user = $_SESSION['user'];
            $connect = true;
        }
        if ($user !== null) {
            $userModel = new UserModel();
            // vérification si l'utilisateur est admin
            if ($user->isAdmin()) {
                // Récupération des articles 
                $articlesModel = new ArticleModel();
                $articles = $articlesModel->getArticles();
                echo $this->twig->render("management-articles.html.twig", ["title" => "Gestion utilisateurs", 'user' => $user, "articles" => $articles, 'connect' => $connect]);
            } else {
                throw new Exception("Vous n'êtes pas autorisé acceder à cette page");
            }
        } else {
            throw new Exception("Vous n'êtes pas autorisé acceder à cette page");
        }
    }
}
