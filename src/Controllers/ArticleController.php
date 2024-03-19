<?php

namespace Application\Controllers;

use Application\ParentController;
use Application\Model\ArticleModel;


class ArticleController extends ParentController
{
    /**
     * addArticlePage
     *
     * @param  mixed $session_user
     * @return void
     */
    public function addArticlePage($session_user)
    {
        $user = null;
        $connect = false;
        if ($session_user !== null) {
            $user = $session_user;
            $connect = true;
        }
        echo $this->twig->render("create-article.html.twig", ['title' => "Article", 'user' => $user, 'connect' => $connect]);
    }


    /**
     * addArticlePage
     *
     * @return void
     */
    public function articlesPage($session_user)
    {
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
     * @param  mixed $session_user
     * @param  string $id
     * @return void
     */
    public function modifyArticle($session_user, string $id)
    {
        $input = $_POST;
        $user = null;
        $connect = false;
        if ($session_user !== null) {
            $user = $session_user;
            $connect = true;
        }
        if ($connect && ($input !== null)) {
            if (!empty($input["input-title"]) && !empty($input["input-chapo"])  && !empty($input["input-content"])) {
                if ((!preg_match("/^[a-zA-Z0-9 ]*$/", $input["input-title"]) || !preg_match("/^[a-zA-Z0-9 ]*$/", $input["input-chapo"]) || !preg_match("/^[a-zA-Z0-9 ]*$/", $input["input-content"]))) {
                    echo $this->twig->render("modify-article.html.twig", ['title' => "Article", "titleArticle" => $input["input-title"], "chapo" => $input["input-chapo"], "content" => $input["input-content"], 'error' => true]);
                } else {
                    $articleModel = new ArticleModel();
                    $article = $articleModel->getArticle($id);
                    if ($user->id == $article->author->id) {
                        $title = $input["input-title"];
                        $chapo = $input["input-chapo"];
                        $content = $input["input-content"];
                        $date_time_zone = new \DateTimeZone('Europe/Paris');
                        $date = new \DateTime('now', $date_time_zone);
                        $articleModel = new ArticleModel();
                        $success = $articleModel->modifyArticle($title, $chapo, $content, $date->format("Y/m/d H:i:s"), $id);
                        if (!$success) {
                            throw new \Exception('Une erreur est surevenu');
                        } else {
                            header('Location: index.php?action=article&id=' . $id);
                        }
                    }
                }
            } else {
                echo $this->twig->render("modify-article.html.twig", ['title' => "Article", "titleArticle" => $input["input-title"], "chapo" => $input["input-chapo"], "content" => $input["input-content"], 'error' => true]);
            }
        }
    }

    /**
     * deleteArticle
     *
     * @param  mixed $session_user
     * @param  string $id
     * @return void
     */
    public function deleteArticle($session_user, string $id)
    {
        $user = null;
        $connect = false;
        if ($session_user !== null) {
            $user = $session_user;
            $connect = true;
        }
        $articleModel = new ArticleModel();
        $article = $articleModel->getArticle($id);
        if ($user->id == $article->author->id) {
            $article_model = new ArticleModel();
            $success = $article_model->deleteArticle($id);
            if (!$success) {
                throw new \Exception('Une erreur est surevenu');
            } else {
                header('Location: index.php?action=articles');
            }
        }
    }

    /**
     * articlePage
     *
     * @param  mixed $session_user
     * @param  string $id
     * @return void
     */
    public function articlePage($session_user, string $id)
    {
        $user = null;
        $connect = false;
        if ($session_user !== null) {
            $user = $session_user;
            $connect = true;
        }
        $articleModel = new ArticleModel();
        $article = $articleModel->getArticle($id);
        echo $this->twig->render("article.html.twig", ['title' => "Article", 'user' => $user, 'connect' => $connect, 'article' => $article]);
    }



    /**
     * modifyPage
     *
     * @param  mixed $session_user
     * @param  string $id
     * @return void
     */
    public function modifyPage($session_user, string $id)
    {
        $user = null;
        $connect = false;
        if ($session_user !== null) {
            $user = $session_user;
            $connect = true;
        }
        $articleModel = new ArticleModel();
        $article = $articleModel->getArticle($id);
        echo $this->twig->render("modification-article.html.twig", ['title' => "Article", 'user' => $user, 'connect' => $connect, 'article' => $article]);
    }


    /**
     * addArticle
     *
     * @param  mixed $session_user
     * @return void
     */
    public function addArticle($session_user)
    {
        $input = $_POST;
        $user = null;
        $connect = false;
        if ($session_user !== null) {
            $user = $session_user;
            $connect = true;
        }
        if ($connect && ($input !== null)) {
            if (!empty($input["input-title"]) && !empty($input["input-chapo"])  && !empty($input["input-content"])) {
                if ((!preg_match("/^[a-zA-Z0-9 ]*$/", $input["input-title"]) || !preg_match("/^[a-zA-Z0-9 ]*$/", $input["input-chapo"]) || !preg_match("/^[a-zA-Z0-9 ]*$/", $input["input-content"]))) {
                    echo $this->twig->render("create-article.html.twig", ['title' => "Article", "titleArticle" => $input["input-title"], "chapo" => $input["input-chapo"], "content" => $input["input-content"], 'error' => true]);
                } else {
                    $title = $input["input-title"];
                    $chapo = $input["input-chapo"];
                    $content = $input["input-content"];
                    $author = $user->id;
                    $date_time_zone = new \DateTimeZone('Europe/Paris');
                    $date = new \DateTime('now', $date_time_zone);
                    $articleModel = new ArticleModel();
                    $articleModel->addArticle($title, $chapo, $content, $author, $date->format("Y/m/d H:i:s"));
                }
            } else {
                echo $this->twig->render("create-article.html.twig", ['title' => "Article", "titleArticle" => $input["input-title"], "chapo" => $input["input-chapo"], "content" => $input["input-content"], 'error' => true]);
            }
        }
    }
}
