<?php
// error_reporting(E_ALL & ~E_DEPRECATED);
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
				// Déconnection 
			case 'disconnect':
				session_destroy();
				header('Location: index.php');
				(new HomepageController())->homepage();
				break;
				// Vérification et enregistrement de l'utilisateur  
			case 'formRegister':
				if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
					header('Location: index.php?action=register');
				}
				(new UserController())->register();
				break;
				// Affichage de la page d'enregistrement
			case 'register':
				(new UserController())->registerPage();
				break;
				// Affichage la page de connexion
			case 'login':
				(new UserController())->loginPage();
				break;
				// Connection
			case 'formLogin':
				// Si déjà connecter
				if (!empty($session_user)) {
					(new HomepageController())->homepage();
				} else {
					(new UserController())->login();
				}
				break;
				// Page de l'ajout d'un article
			case 'createArticle':
				(new ArticleController())->addArticlePage();
				break;
				// Ajout de l'article
			case 'addArticle':
				(new ArticleController())->addArticle();
				break;
				// Page listant tout les articles
			case 'articles':
				(new ArticleController())->articlesPage();
				break;
				// Page de l'article
			case 'article':
				if (isset($_GET['id-article']) && $_GET['id-article'] > 0) {
					$id_article = $_GET['id-article'];
					(new ArticleController())->articlePage($id_article, $id_comment, "");
				}
				break;
				// Page de modification de l'article
			case 'modifyArticlePage':
				if (isset($_GET['id-article']) && $_GET['id-article'] > 0) {
					$id_article = $_GET['id-article'];
					(new ArticleController())->modifyPage($id_article);
				}
				break;
				// modification de l'article
			case 'modify-article':
				if (isset($_GET['id-article']) && $_GET['id-article'] > 0) {
					$id_article = $_GET['id-article'];
					(new ArticleController())->modifyArticle($id_article);
				}
				break;
				// Suppression de l'article
			case 'delete':
				if (isset($_GET['id-article']) && $_GET['id-article'] > 0) {
					$id_article = $_GET['id-article'];
					(new ArticleController())->deleteArticle($id_article);
				}
				break;
				// Ajout d'un commentaire
			case 'addComment':
				if (isset($_GET['id-article']) && $_GET['id-article'] > 0) {
					$id_article = $_GET['id-article'];
					(new CommentController())->addComment($id_article);
				}
				break;
				// Affichage de l'article du commentaire
			case 'modify-comment':
				if (isset($_GET['id-article']) && $_GET['id-article'] > 0) {
					$id_article = $_GET['id-article'];
					if (isset($_GET['comment']) && $_GET['comment'] > 0) {
						$id_comment = $_GET['comment'];
						(new ArticleController())->articlePage($id_article, $id_comment, "");
					}
				}
				break;
				// modification d'un commentaire
			case 'modify-action':
				if (isset($_GET['id-article']) && $_GET['id-article'] > 0) {
					$id_article = $_GET['id-article'];
					if (isset($_GET['comment']) && $_GET['comment'] > 0) {
						$id_comment = $_GET['comment'];

						(new CommentController())->modifyComment($id_comment, $id_article);
					}
				}
				break;
				// Suppression du commentaire
			case 'delete-comment':
				if (isset($_GET['id-article']) && $_GET['id-article'] > 0) {
					$id_article = $_GET['id-article'];
					if (isset($_GET['comment']) && $_GET['comment'] > 0) {
						$id_comment = $_GET['comment'];

						(new CommentController())->deleteComment($id_comment, $id_article);
					}
				}
				break;
				// Validation du commentaire
			case 'validate-comment':
				if (isset($_GET['id-article']) && $_GET['id-article'] > 0) {
					$id_article = $_GET['id-article'];
					if (isset($_GET['comment']) && $_GET['comment'] > 0) {
						$id_comment = $_GET['comment'];

						(new CommentController())->valideComment($id_comment, $id_article);
					}
				}
				break;
			// Affichage de la page de gestion des utilisateur
			case 'management-user':
				(new UserController())->managementUser();

				break;
			// Affichage de la page de gestion des articles 
			case 'management-articles':
				(new ArticleController())->managementArticles();
				break;
			// Affichage de la page d'accueil des management
			case 'management':
				(new ManagementController())->managementPage();
				break;
			// Changer le role d'un utilisateur
			case 'change-role':
				if (isset($_GET['id_user']) && $_GET['id_user'] > 0) {
					$id_user = $_GET['id_user'];
					(new UserController())->changeRole($id_user);
				}
				break;
			// Suppression d'un utilisateur
			case 'delete-user':
				if (isset($_GET['id_user']) && $_GET['id_user'] > 0) {
					$id_user = $_GET['id_user'];
					(new UserController())->deleteUser($id_user);
				}
				break;
			// Affichage du profile
			case 'profile':
				(new UserController())->profile();
				break;
			// Affichage de la page de modification du mdp 
			case 'change-password-form':

				(new UserController())->changePasswordForm();
				break;
			// Changement du mots de passe
			case 'change-password':
				if (isset($_GET['id_user']) && $_GET['id_user'] > 0) {
					$id_user = $_GET['id_user'];
					(new UserController())->modifyPassword();
				}
				break;
			// Page de formulaire de mots de passe oublié
			case 'forgot-password-form':
				(new UserController())->forgotPasswordPage();
				break;
			// Envoi du mots de passe
			case 'send-password':
				(new UserController())->sendPassword();
				break;
			// Envoie de l'email de contacte
			case 'form-contact':
				(new HomepageController())->sendMail();
				break;
			// Confirmation de l'envoie du mail
			case 'confirm-form':
				(new HomepageController())->confirmForm();
				break;
			// Confirmation de la suppression de l'utilisateur 
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
