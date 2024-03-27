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
$session_user = null;
$id_user = null;
$id_comment = null;
if (isset($_SESSION['user'])) {
    $session_user = $_SESSION['user'];
}
try {
    if (isset($_GET['action']) && $_GET['action'] !== '') {
        switch ($_GET['action']) {
            case 'disconnect':
                session_destroy();
                header('Location: index.php');
                (new HomepageController())->homepage($session_user);
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
                    (new HomepageController())->homepage($session_user);
                } else {
                    (new UserController())->login();
                }
                break;
            case 'createArticle':
                (new ArticleController())->addArticlePage($session_user);
                break;
            case 'addArticle':
                (new ArticleController())->addArticle($session_user);
                break;
            case 'articles':
                (new ArticleController())->articlesPage($session_user);
                break;
            case 'article':
                if (isset($_GET['id-article']) && $_GET['id-article'] > 0) {
                    $id_article = $_GET['id-article'];
                    (new ArticleController())->articlePage($session_user, $id_article, $id_comment);
                }
                break;
            case 'modifyArticlePage':
                if (isset($_GET['id-article']) && $_GET['id-article'] > 0) {
                    $id_article = $_GET['id-article'];
                    (new ArticleController())->modifyPage($session_user, $id_article);
                }
                break;
            case 'modify-article':
                if (isset($_GET['id-article']) && $_GET['id-article'] > 0) {
                    $id_article = $_GET['id-article'];
                    (new ArticleController())->modifyArticle($session_user, $id_article);
                }
                break;
            case 'delete':
                if (isset($_GET['id-article']) && $_GET['id-article'] > 0) {
                    $id_article = $_GET['id-article'];
                    (new ArticleController())->deleteArticle($session_user, $id_article);
                }
                break;
            case 'addComment':
                if (isset($_GET['id-article']) && $_GET['id-article'] > 0) {
                    $id_article = $_GET['id-article'];
                    (new CommentController())->addComment($session_user, $id_article);
                }
                break;

            case 'modify-comment':
                if (isset($_GET['id-article']) && $_GET['id-article'] > 0) {
                    $id_article = $_GET['id-article'];
                    if (isset($_GET['comment']) && $_GET['comment'] > 0) {
                        $id_comment = $_GET['comment'];
                        (new ArticleController())->articlePage($session_user, $id_article, $id_comment);
                    }
                }
                break;

            case 'modify-action':
                if (isset($_GET['id-article']) && $_GET['id-article'] > 0) {
                    $id_article = $_GET['id-article'];
                    if (isset($_GET['comment']) && $_GET['comment'] > 0) {
                        $id_comment = $_GET['comment'];

                        (new CommentController())->modifyComment($session_user, $id_comment, $id_article);
                    }
                }
                break;

            case 'delete-comment':
                if (isset($_GET['id-article']) && $_GET['id-article'] > 0) {
                    $id_article = $_GET['id-article'];
                    if (isset($_GET['comment']) && $_GET['comment'] > 0) {
                        $id_comment = $_GET['comment'];

                        (new CommentController())->deleteComment($session_user, $id_comment, $id_article);
                    }
                }
                break;
            case 'validate-comment':
                if (isset($_GET['id-article']) && $_GET['id-article'] > 0) {
                    $id_article = $_GET['id-article'];
                    if (isset($_GET['comment']) && $_GET['comment'] > 0) {
                        $id_comment = $_GET['comment'];

                        (new CommentController())->valideComment($session_user, $id_comment, $id_article);
                    }
                }
                break;
            case 'management-user':
                (new UserController())->managementUser($session_user);

                break;
            case 'management-articles':
                (new ArticleController())->managementArticles($session_user);
                break;
            case 'management':
                (new ManagementController())->managementPage($session_user);
                break;
            case 'change-role':
                if (isset($_GET['email_user']) && $_GET['email_user'] > 0) {
                    $email_user = $_GET['email_user'];
                    (new UserController())->changeRole($session_user, $email_user);
                }
                break;

            case 'delete-user':
                if (isset($_GET['id_user']) && $_GET['id_user'] > 0) {
                    $id_user = $_GET['id_user'];
                    (new UserController())->deleteUser($session_user, $id_user);
                }
                break;
            case 'profile':
                (new UserController())->profile($session_user);
                break;
            case 'change-password-form':

                (new UserController())->changePasswordForm($session_user);
                break;
            case 'change-password':
                if (isset($_GET['id_user']) && $_GET['id_user'] > 0) {
                    $id_user = $_GET['id_user'];
                    (new UserController())->modifyPassword($session_user);
                }
                break;
            case 'forgot-password-form':
                (new UserController())->forgotPasswordPage($session_user);
                break;

            case 'send-password':
                (new UserController())->sendPassword($session_user);
                break;
            default:
                throw new Exception("La page que vous recherchez n'existe pas.");
        }
    } else {

        (new HomepageController())->homepage($session_user);
    }
} catch (Exception $e) {
    $errorMessage = $e->getMessage();
    (new HomepageController())->errorPage( $session_user , $errorMessage);
}
