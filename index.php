<?php
require_once("./vendor/autoload.php");


spl_autoload_register(function ($fqcn) {
    $path = str_replace(['Application', '\\'], ['src', '/'], $fqcn) . '.php';
    require_once($path);
});

use Application\Controllers\ArticleController;
use Application\Controllers\CommentController;
use Application\Controllers\HomepageController;
use Application\Controllers\UserController;
use Application\Controllers\ManagementController;

session_start();
$id_user = null;
$id_comment = null;
if (isset($_SESSION['user'])) {
    $session_user = $_SESSION['user'];
} else {
    $session_user = null;
}
try {
    if (isset($_GET['action']) && $_GET['action'] !== '') {
        switch ($_GET['action']) {
            case 'disconnect':
                session_destroy();
                header('Location: index.php');
                (new HomepageController())->homepage();
                break;
            case 'formRegister':
                if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                    header('Location: index.php?action=register');
                }
                (new UserController())->register();
                break;
            case 'register':
                (new UserController())->registerPage();
                break;
            case 'login':
                (new UserController())->loginPage();
                break;
            case 'formLogin':
                if (!empty($session_user)) {
                    (new HomepageController())->homepage();
                } else {
                    (new UserController())->login();
                }
                break;
            case 'createArticle':
                (new ArticleController())->addArticlePage();
                break;
            case 'addArticle':
                (new ArticleController())->addArticle();
                break;
            case 'articles':
                (new ArticleController())->articlesPage();
                break;
            case 'article':
                if (isset($_GET['id-article']) && $_GET['id-article'] > 0) {
                    $id_article = $_GET['id-article'];
                    (new ArticleController())->articlePage($id_article, $id_comment, "");
                }
                break;
            case 'modifyArticlePage':
                if (isset($_GET['id-article']) && $_GET['id-article'] > 0) {
                    $id_article = $_GET['id-article'];
                    (new ArticleController())->modifyPage($id_article);
                }
                break;
            case 'modify-article':
                if (isset($_GET['id-article']) && $_GET['id-article'] > 0) {
                    $id_article = $_GET['id-article'];
                    (new ArticleController())->modifyArticle($id_article);
                }
                break;
            case 'delete':
                if (isset($_GET['id-article']) && $_GET['id-article'] > 0) {
                    $id_article = $_GET['id-article'];
                    (new ArticleController())->deleteArticle($id_article);
                }
                break;
            case 'addComment':
                if (isset($_GET['id-article']) && $_GET['id-article'] > 0) {
                    $id_article = $_GET['id-article'];
                    (new CommentController())->addComment($id_article);
                }
                break;

            case 'modify-comment':
                if (isset($_GET['id-article']) && $_GET['id-article'] > 0) {
                    $id_article = $_GET['id-article'];
                    if (isset($_GET['comment']) && $_GET['comment'] > 0) {
                        $id_comment = $_GET['comment'];
                        (new ArticleController())->articlePage($id_article, $id_comment, "");
                    }
                }
                break;

            case 'modify-action':
                if (isset($_GET['id-article']) && $_GET['id-article'] > 0) {
                    $id_article = $_GET['id-article'];
                    if (isset($_GET['comment']) && $_GET['comment'] > 0) {
                        $id_comment = $_GET['comment'];

                        (new CommentController())->modifyComment($id_comment, $id_article);
                    }
                }
                break;

            case 'delete-comment':
                if (isset($_GET['id-article']) && $_GET['id-article'] > 0) {
                    $id_article = $_GET['id-article'];
                    if (isset($_GET['comment']) && $_GET['comment'] > 0) {
                        $id_comment = $_GET['comment'];

                        (new CommentController())->deleteComment($id_comment, $id_article);
                    }
                }
                break;
            case 'validate-comment':
                if (isset($_GET['id-article']) && $_GET['id-article'] > 0) {
                    $id_article = $_GET['id-article'];
                    if (isset($_GET['comment']) && $_GET['comment'] > 0) {
                        $id_comment = $_GET['comment'];

                        (new CommentController())->valideComment($id_comment, $id_article);
                    }
                }
                break;
            case 'management-user':
                (new UserController())->managementUser();

                break;
            case 'management-articles':
                (new ArticleController())->managementArticles();
                break;
            case 'management':
                (new ManagementController())->managementPage();
                break;
            case 'change-role':
                if (isset($_GET['id_user']) && $_GET['id_user'] > 0) {
                    $id_user = $_GET['id_user'];
                    (new UserController())->changeRole($id_user);
                }
                break;

            case 'delete-user':
                if (isset($_GET['id_user']) && $_GET['id_user'] > 0) {
                    $id_user = $_GET['id_user'];
                    (new UserController())->deleteUser($id_user);
                }
                break;
            case 'profile':
                (new UserController())->profile();
                break;
            case 'change-password-form':

                (new UserController())->changePasswordForm();
                break;
            case 'change-password':
                if (isset($_GET['id_user']) && $_GET['id_user'] > 0) {
                    $id_user = $_GET['id_user'];
                    (new UserController())->modifyPassword();
                }
                break;
            case 'forgot-password-form':
                (new UserController())->forgotPasswordPage();
                break;

            case 'send-password':
                (new UserController())->sendPassword();
                break;
            case 'form-contact':
                (new HomepageController())->sendMail();
                break;
            case 'confirm-form':
                (new HomepageController())->confirmForm();
                break;
            case 'confirm-delete':
                if (isset($_GET['id_user']) && $_GET['id_user'] > 0) {
                    $id_user = $_GET['id_user'];
                    (new UserController())->confirmDelete($id_user);
                }
                break;
            default:
                throw new Exception("La page que vous recherchez n'existe pas.");
        }
    } else {

        (new HomepageController())->homepage();
    }
} catch (Exception $e) {
    $errorMessage = $e->getMessage();
    (new HomepageController())->errorPage($errorMessage);
}
