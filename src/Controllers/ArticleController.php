<?php

namespace Application\Controllers;

use Application\ParentController;
use Application\Model\ArticleModel;
use Application\Model\CommentModel;

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
     * @param mixed $session_user
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
     * @param  string $id_article
     * @return void
     */
    public function modifyArticle($session_user, string $id_article)
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
                    $article = $articleModel->getArticle($id_article);
                    if ($user->id == $article->author->id) {
                        $title = $input["input-title"];
                        $chapo = $input["input-chapo"];
                        $content = $input["input-content"];
                        $date_time_zone = new \DateTimeZone('Europe/Paris');
                        $date = new \DateTime('now', $date_time_zone);
                        $articleModel = new ArticleModel();
                        $success = $articleModel->modifyArticle($title, $chapo, $content, $date->format("Y/m/d H:i:s"), $id_article);
                        if (!$success) {
                            throw new \Exception('Une erreur est surevenu');
                        } else {
                            header('Location: index.php?action=article&id-article=' . $id_article);
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
     * @param  string $id_article
     * @return void
     */
    public function deleteArticle($session_user, string $id_article)
    {
        $user = null;
        if ($session_user !== null) {
            $user = $session_user;
        }
        $articleModel = new ArticleModel();
        $article = $articleModel->getArticle($id_article);
        if ($user->id == $article->author->id ||$user->admin) {
            $article_model = new ArticleModel();
            $success_article = $article_model->deleteArticle($id_article);
            $commentModel = new CommentModel();
            $success_comment = $commentModel->deleteComment($id_article);
            if (!$success_article && !$success_comment) {
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
     * @param  string $id_article
     * @return void
     */
    public function articlePage($session_user, string $id_article, $id_comment)
    {
        $user = null;
        $connect = false;
        if ($session_user !== null) {
            $user = $session_user;
            $connect = true;
        }
        $articleModel = new ArticleModel();
        $article = $articleModel->getArticle($id_article);
        $commentModel = new CommentModel();
        $comments = $commentModel->getComments($id_article);
        if ($id_comment !== null) {
            if ($user->id !== $comments[$id_comment]->user->id) {
                $id_comment = null;
            }
        }
        // var_dump($comments);
        echo $this->twig->render("article.html.twig", ['title' => "Article", 'modifyState' => $id_comment, 'comments' => $comments, 'user' => $user, 'connect' => $connect, 'article' => $article]);
    }



    /**
     * modifyPage
     *
     * @param  mixed $session_user
     * @param  string $id
     * @return void
     */
    public function modifyPage($session_user, string $id_article)
    {
        $user = null;
        $connect = false;
        if ($session_user !== null) {
            $user = $session_user;
            $connect = true;
        }
        $articleModel = new ArticleModel();
        $article = $articleModel->getArticle($id_article);
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
                    $success = $articleModel->addArticle($title, $chapo, $content, $author, $date->format("Y/m/d H:i:s"));
                    if (!$success) {
                        throw new \Exception('Une erreur est surevenu');
                    } else {
                        header('Location: index.php?action=articles');
                    }
                }
            } else {
                echo $this->twig->render("create-article.html.twig", ['title' => "Article", "titleArticle" => $input["input-title"], "chapo" => $input["input-chapo"], "content" => $input["input-content"], 'error' => true]);
            }
        }
    }

    public function managementArticles($session_user){
        {
            $user = null;
            $connect = false;
            if ($session_user !== null) {
                $user = $session_user;
                $connect = true;
            }
            if($user !== null) {
                if ($user->admin) {
                    $articlesModel = new ArticleModel();
                    $articles = $articlesModel->getArticles();
                    echo $this->twig->render("management-articles.html.twig", ["title" => "Gestion utilisateurs", 'user' => $user, "articles" => $articles, 'connect' => $connect]);
                }else{
                    throw new \Exception("Vous n'êtes pas autorisé acceder à cette page");
                }
            }
            else{
                throw new \Exception("Vous n'êtes pas autorisé acceder à cette page");
            }
        }
    }
}
