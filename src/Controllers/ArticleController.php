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
     * addArticlePage
     *
     * @return void
     */
    public function addArticlePage(): void
    {
        global $session_user;
        $user = null;
        $connect = false;
        if ($session_user !== null) {
            $user = $session_user;
            $connect = true;
        }
        
        $userModel = new UserModel();
        $users = $userModel->getUsers();
        echo $this->twig->render("create-article.html.twig", ['title' => "Article", "users" => $users, 'user' => $user, 'connect' => $connect]);
    }


    /**
     * addArticlePage
     * @return void
     */
    public function articlesPage(): void
    {
        global $session_user;
        $user = null;
        $connect = false;
        if ($session_user !== null) {
            $user = $session_user;
            $connect = true;
        }
        
        $articleModel = new ArticleModel();
        $articles = $articleModel->getArticles();

        echo $this->twig->render("articles.html.twig", ['title' => "Article", 'user' => $user, 'connect' => $connect, 'articles' => $articles]);
    }

    /**
     * modifyArticle
     *
     * @param  string $id_article
     * @return void
     */
    public function modifyArticle(string $id_article): void
    {
        global $session_user;
        $input = $_POST;
        $user = null;
        $connect = false;
        if ($session_user !== null) {
            $user = $session_user;
            $connect = true;
        }
        
        $articleModel = new ArticleModel();
        $article = $articleModel->getArticle($id_article);
        if ($connect && ($input !== null)) {
            if (!empty($input["input-title"]) && !empty($input["input-chapo"])  && !empty($input["input-content"])) {
                if ($user->id == $article->author->id || $user->isAdmin()) {
                    $title = htmlspecialchars($input["input-title"]);
                    $chapo = htmlspecialchars($input["input-chapo"]);
                    $content = htmlspecialchars($input["input-content"]);
                    $author = $input["select-author"];
                    if ($author == "") {
                        throw new Exception('Une erreur est surevenu');
                    }
                    $date_time_zone = new \DateTimeZone('Europe/Paris');
                    $date = new \DateTime('now', $date_time_zone);
                    $userModel = new UserModel();
                    $user_exist = $userModel->getUser($author);
                    if (is_null($user_exist)) {
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
     * deleteArticle
     *
     * @param  string $id_article
     * @return void
     */
    public function deleteArticle(string $id_article): void
    {
        global $session_user;
        $user = null;
        $connect = false;
        if ($session_user !== null) {
            $user = $session_user;
            $connect = true;
        }
        
        $articleModel = new ArticleModel();
        $article = $articleModel->getArticle($id_article);
        $userModel = new UserModel();
        if ($user->id == $article->author->id || $user->isAdmin()) {
            $article_model = new ArticleModel();
            $success_article = $article_model->deleteArticle($id_article);
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
     * articlePage
     *
     * @param  string $id_article
     * @return void
     */
    public function articlePage(string $id_article, mixed $id_comment, string $message): void
    {
        global $session_user;
        $user = null;
        $connect = false;
        if ($session_user !== null) {
            $user = $session_user;
            $connect = true;
        }

        $articleModel = new ArticleModel();
        $article = $articleModel->getArticle($id_article);
        $article->content = html_entity_decode($article->content);
        $commentModel = new CommentModel();
        $comments = $commentModel->getComments($id_article);
        if ($id_comment !== null) {
            if ($user->id !== $comments[$id_comment]->user->id) {
                $id_comment = null;
            }
        }
        $this->twigAddVariableJS('test', 'toto');
        echo $this->twig->render("article.html.twig", ['title' => "Article", 'modifyState' => $id_comment, 'comments' => $comments, 'user' => $user, 'connect' => $connect, 'article' => $article, 'message' => $message]);
    }



    /**
     * modifyPage
     *
     * @param  string $id
     * @return void
     */
    public function modifyPage(string $id_article): void
    {
        global $session_user;
        $user = null;
        $connect = false;
        if ($session_user !== null) {
            $user = $session_user;
            $connect = true;
        }
        $articleModel = new ArticleModel();
        $article = $articleModel->getArticle($id_article);
        $article->content = html_entity_decode($article->content);
        $userModel = new UserModel();
        $users = $userModel->getUsers();

        echo $this->twig->render("modification-article.html.twig", ['title' => "Article", 'users' => $users, 'user' => $user, 'connect' => $connect, 'article' => $article]);
    }


    /**
     * addArticle
     *
     * @return void
     */
    public function addArticle(): void
    {
        global $session_user;
        $input = $_POST;
        $user = null;
        $connect = false;
        if ($session_user !== null) {
            $user = $session_user;
            $connect = true;
        }
        if ($connect && ($input !== null)) {
            if (!empty($input["input-title"]) && !empty($input["input-chapo"])  && !empty($input["input-content"])) {
                if ((!preg_match("/^[a-zA-Z0-9 ]*$/", $input["input-title"]) || !preg_match("/^[a-zA-Z0-9 ]*$/", $input["input-chapo"]))) {
                    echo $this->twig->render("create-article.html.twig", ['title' => "Article",  'connect' => $connect, "user" => $user, "titleArticle" => $input["input-title"], "chapo" => $input["input-chapo"], "content" => $input["input-content"], 'error' => true]);
                } else {
                    $title = $input["input-title"];
                    $chapo = $input["input-chapo"];
                    $content = htmlspecialchars($input["input-content"]);
                    if ($user->isAdmin()) {
                        $author = $input["select-author"];
                        if ($author == '') {
                            throw new Exception('Une erreur est surevenu');
                        }
                    } else {
                        $author = $user->id;
                    }
                    $date_time_zone = new \DateTimeZone('Europe/Paris');
                    $date = new \DateTime('now', $date_time_zone);
                    $userModel = new UserModel();
                    $user_exist = $userModel->getUser($author);
                    if (is_null($user_exist)) {
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
     * managementArticles
     *
     * @return void
     */
    public function managementArticles(): void
    {
        global $session_user;
        $user = null;
        $connect = false;
        if ($session_user !== null) {
            $user = $session_user;
            $connect = true;
        }
        if ($user !== null) {
            $userModel = new UserModel();
            if ($user->isAdmin()) {
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
