<?php
require_once("./vendor/autoload.php");

// $loader = new Twig_Loader_Filesystem(__DIR__ .'/templates');
// $twig = new Twig_Environment($loader , [
//     'cache'=> false,
// ]);

spl_autoload_register(function ($fqcn) {
    $path = str_replace(['Application', '\\'], ['src', '/'], $fqcn) . '.php';
    require_once($path);
});

use Application\Controllers\ArticleController;
use Application\Controllers\HomepageController;
use Application\Controllers\UserController;

session_start();
$session_user = null;
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
            case 'articles':
                (new ArticleController())->articlesPage($session_user);
                break;
            case 'article':
                if (isset($_GET['id']) && $_GET['id'] > 0) {
                    $id = $_GET['id'];
                    (new ArticleController())->articlePage($session_user, $id);
                }
                break;
            case 'modifyArticlePage':
                if (isset($_GET['id']) && $_GET['id'] > 0) {
                    $id = $_GET['id'];
                    (new ArticleController())->modifyPage($session_user, $id);
                }
                break;
            case 'modify-article':
                if (isset($_GET['id']) && $_GET['id'] > 0) {
                    $id = $_GET['id'];
                    (new ArticleController())->modifyArticle($session_user, $id);
                }
                break;
            case 'delete':
                if (isset($_GET['id']) && $_GET['id'] > 0) {
                    $id = $_GET['id'];
                    (new ArticleController())->deleteArticle($session_user, $id);
                }
                break;
            default:
                throw new Exception("La page que vous recherchez n'existe pas.");
        }
    } else {

        (new HomepageController())->homepage($session_user);
    }
} catch (Exception $e) {
    $errorMessage = $e->getMessage();
    echo $errorMessage;
    // echo $twig->render('error.html.twig', [ 'error'=>$errorMessage , 'title' => 'Erreur']); 
}
