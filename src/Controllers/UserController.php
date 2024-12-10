<?php

namespace Application\Controllers;

use Application\ParentController;

use Application\Model\UserModel;

class UserController extends ParentController
{
    /**
     * Retourn la page d'enregistrement
     *
     * @return void
     */
    public function registerPage(): void
    {
        $user = null;
        $connect = false;
        if (isset($_SESSION['user'])) {
            $user = $_SESSION['user'];
            $connect = true;
        }
        
        $twig = $this->twig;
        echo $twig->render("register.html.twig", ['title' => 'Enregistrement']);
    }
    /**
     * Retourne la page de connexion
     *@param mixed $session_user
     * @return void
     */
    public function loginPage(): void
    {
        $user = null;
        $connect = false;
        if (isset($_SESSION['user'])) {
            $user = $_SESSION['user'];
            $connect = true;
        }
        
        if ($user === null) {
            $twig = $this->twig;
            echo $twig->render("login.html.twig", ['title' => 'Connexion']);
        } else {
            throw new \Exception("Vous êtes déjà connecter.");
        }
    }
    /**
     * Enregistrement de l'utilisateur
     * 
     *
     * @return void
     */
    public function register(): void
    {
        $input = $_POST;
        $name = null;
        $firstname = null;
        $email = null;
        $password_hash = null;
        $user = null;
        $connect = false;
        if (isset($_SESSION['user'])) {
            $user = $_SESSION['user'];
            $connect = true;
        }
        
        //Vérification si les input ne sont pas vide
        if ($input !== null) {

            if (!empty($input["input-name"]) && !empty($input["input-firstname"]) && !empty($input["input-email"]) && !empty($input["inputPassword"])) {
                if (!preg_match("/^[a-zA-Z ]*$/", $input["input-name"]) || !preg_match("/^[a-zA-Z ]*$/", $input["input-firstname"]) || !preg_match("/^[a-zA-Z0-9][^\s\<\>]*$/", $input["input-email"])) { //si c'est pas un mot
                    echo $this->twig->render("register.html.twig", ['title' => 'Enregistrement', 'name' => $input["input-name"], 'firstname' => $input["input-firstname"], 'email' => $input["input-email"], 'password' => $input["inputPassword"], 'hacking' => true]);
                } else { 
                    $name = $input['input-name'];
                    $firstname = $input['input-firstname'];
                    //hashage du password
                    $password_hash = password_hash($_POST['inputPassword'], PASSWORD_DEFAULT, ['cost' => 12]);
                }

                if (filter_var($input["input-email"], FILTER_VALIDATE_EMAIL)) {
                    $email = $input['input-email'];
                } else {
                    echo $this->twig->render("register.html.twig", ['title' => 'Enregistrement', 'name' => $input["input-name"], 'firstname' => $input["input-firstname"], 'email' => $input["input-email"], 'password' => $input["inputPassword"], 'hacking' => true]);
                }
                //Vérifier le password
                $userModel = new UserModel();
                $cheked_email = $userModel->checkedUser($email);
                // Si email n'est pas en base de données
                if ($cheked_email === null) {
                    $userModel->addUser($name, $firstname, $email, $password_hash);
                    $user = $userModel->checkedUser($email);
                    $user->password = null;
                    $_SESSION['user'] = $user;
                    header('Location: index.php');
                } else {
                    $twig = $this->twig;
                    echo $twig->render("register.html.twig", ['title' => 'Enregistrement', 'name' => $input["input-name"], 'firstname' => $input["input-firstname"], 'email' => $input["input-email"], 'password' => $input["inputPassword"], 'exist' => true]);
                }
            } else {
                $twig = $this->twig;
                echo $twig->render("register.html.twig", ['title' => 'Enregistrement', 'name' => $input["input-name"], 'firstname' => $input["input-firstname"], 'email' => $input["input-email"], 'password' => $input["inputPassword"], 'error' => true]);
            }
        } else {
            throw new \Exception("Une erreur est survenu veuillez rééssayer ulrérieurement");
        }
    }
    /**
     * Connexion de l'utilisateur
     *
     * @return void
     */
    public function login(): void
    {
        $user = null;
        $connect = false;
        if (isset($_SESSION['user'])) {
            $user = $_SESSION['user'];
            $connect = true;
        }
        
        $input = $_POST;
        $email = null;
        // Si le POST n'est pas null
        if ($input !== null) {
            // Si l'email et le mots de passe sont renseigné
            if (!empty($input["input-email"]) && !empty($input["inputPassword"])) {
                if (!preg_match("/^[a-zA-Z0-9][^\s\<\>]*$/", $input["input-email"])) { //si c'est pas un mot
                    echo $this->twig->render("login.html.twig", ['title' => 'Connexion', 'error' => true]);
                } else {

                    $email = $input['input-email'];
                    $user_model = new UserModel();
                    // Vérification de l'email 
                    $user = $user_model->checkedUser($email);
                    // Si user non trouvé
                    if ($user === null) {
                        echo $this->twig->render("login.html.twig", ['title' => 'Connexion', 'error' => true]);
                    } else {
                        // Vérification du mots de passe
                        $checked = password_verify($_POST['inputPassword'], $user->password);
                        // Mots de passe valide
                        if ($checked) {
                            $user->password = "";
                            $_SESSION['user'] = $user;
                            header('Location: index.php');
                        } else {
                            echo $this->twig->render("login.html.twig", ['title' => 'Connexion', 'error' => true]);
                        }
                    }
                }
            } else {
                echo $this->twig->render("login.html.twig", ['title' => 'Connexion', 'error' => true]);
            }
        } else {
            echo $this->twig->render("login.html.twig", ['title' => 'Connexion', 'error' => true]);
        }
    }
    /**
     * managementUser
     *
     * @return void
     */
    public function managementUser(): void
    {
        $user = null;
        $connect = false;
        if (isset($_SESSION['user'])) {
            $user = $_SESSION['user'];
            $connect = true;
        }
        
        if ($user !== null) {
            $userModel = new UserModel();
            // Vérification si l'utilisateur à le role admin 
            if ($user->isAdmin()) {
                $usersModel = new UserModel();
                // Récupération de tout les utilisateurs du site 
                $users = $usersModel->getUsers();
                echo $this->twig->render("management-users.html.twig", ["title" => "Gestion utilisateurs", 'user' => $user, "users" => $users, 'connect' => $connect]);
            } else {
                throw new \Exception("Vous n'êtes pas autorisé acceder à cette page");
            }
        } else {
            throw new \Exception("Vous n'êtes pas autorisé acceder à cette page");
        }
    }
    /**
     * Changement du role d'un utilisateur 
     *
     * @param  string $email_user
     * @return void
     */
    public function changeRole(string $user_id): void
    {
        // Récupéré son rôle
        $select = $_POST['select-role'];
        $user_session = null;
        $connect = false;
        $role = null;

        if ($select == "admin") {
            $role = 'admin';
        } else {
            $role = 'user';
        }
        // Vérification de l'utilisateur qui change le rôle 
        if (isset($_SESSION['user'])) {
            $user_session = $_SESSION['user'];
            $connect = true;
        }
        $userModel = new UserModel();
        // Vérification si le utilisateur connecter est admin 
        if ($user_session->isAdmin()) {
            // Récupération de l'utilisateur
            $userModel = new UserModel();
            $user = $userModel->getUser($user_id);
            // récupération du nombre d'admin restant 
            $users_model = new UserModel();
            $number_admin = $users_model->checkAdmin();
            if ($user->isAdmin() !== $role) {
                
                if (intval($number_admin['number_admin']) > 1 || $select == "admin") {
                    // Modifier le rôle
                    $userModel = new UserModel();
                    $success = $userModel->modifyRole($user_id, $role);
                    if (!$success) {
                        throw new \Exception('Une erreur est surevenu');
                    }
                    if ($user_session->id == $user_id) {
                        session_destroy();
                        header('Location: index.php');
                    } else {
                        $this->managementUser();
                    }
                } else {
                    $usersModel = new UserModel();
                    $users = $usersModel->getUsers();
                    echo $this->twig->render("management-users.html.twig", ["title" => "Gestion utilisateurs", 'user' => $user, "users" => $users, 'connect' => $connect, 'errorDelete' => true]);
                }
            }

            
        }
    }

    /**
     * Confirmer la suppression de l'utilisateur 
     *
     * @param  string $id_user
     * @return void
     */
    public function confirmDelete(string $id_user): void
    {
        $user = null;
        $connect = false;
        if (isset($_SESSION['user'])) {
            $user = $_SESSION['user'];
            $connect = true;
        }
        
        if ($user !== null) {
            if ($user->isAdmin()) {
                $usersModel = new UserModel();
                $users = $usersModel->getUsers();
                echo $this->twig->render("management-users.html.twig", ["title" => "Gestion utilisateurs", 'user' => $user, "users" => $users, 'connect' => $connect, "confirm" => true, "idUser" => $id_user]);
            }
        }
    }

    /**
     * Supprimer l'utilisateur 
     *
     * @param  string $id_user
     * @return void
     */
    public function deleteUser(string $id_user): void
    {
        $user = null;
        $connect = false;
        if (isset($_SESSION['user'])) {
            $user = $_SESSION['user'];
            $connect = true;
        }
        // Vérification de l'user connecter
        if ($user->isAdmin()) {
            $usersModel = new UserModel();
            $number_admin = $usersModel->checkAdmin();
            $user_model = new UserModel();
            $user_model_delete = $user_model->getUser($id_user);
            // Vérification du nombre restant d'admin 
            if (intval($number_admin['number_admin']) > 1 || $user_model_delete->role == 'user') {
                $users_model = new UserModel();
                $success = $users_model->deleteUser($id_user);
                if (!$success) {
                    throw new \Exception('Une erreur est surevenu');
                }
                // Si l'utilisateur supprimer est l'utilisateur connecter forcer la déconnexion 
                if ($user->id == $id_user) {
                    header('Location: index.php');
                    session_destroy();
                } else {
                    $this->managementUser();
                }
            } else {
                $usersModel = new UserModel();
                $users = $usersModel->getUsers();
                echo $this->twig->render("management-users.html.twig", ["title" => "Gestion utilisateurs", 'user' => $user, "users" => $users, 'connect' => $connect, 'errorDelete' => true]);
            }
        }
    }
    /**
     * Page de profile 
     *
     * @return void
     */
    public function profile(): void
    {
        $user = null;
        $connect = false;
        if (isset($_SESSION['user'])) {
            $user = $_SESSION['user'];
            $connect = true;
            echo $this->twig->render("profile.html.twig", ["title" => "Mon profile", 'user' => $user, 'connect' => $connect]);
        } else {
            throw new \Exception('Vous devez être connecté pour acceder à cette page');
        }

        
    }

    /**
     * Formulaire de modificcation de mots de passe
     *
     * @return void
     */
    public function changePasswordForm(): void
    {
        $user = null;
        $connect = false;
        if (isset($_SESSION['user'])) {
            $user = $_SESSION['user'];
            $connect = true;
            echo $this->twig->render("change-password-form.html.twig", ["title" => "Mon profile", 'user' => $user, 'connect' => $connect]);
        } else {
            throw new \Exception('Vous devez être connecté pour acceder à cette page');
        }
        
    }

    /**
     * Modifier le mots de passe
     *
     * @return void
     */
    public function modifyPassword(): void
    {
        $input = $_POST;
        $user = null;
        $connect = false;
        if (isset($_SESSION['user'])) {
            $user = $_SESSION['user'];
            $connect = true;
        }
        
        if ($user === null) {
            throw new \Exception('Email inconnu');
        } else {
            $email = $user->email;
            $user_id = $user->id;
            // Récupération de l'utilisateur 
            $user_model = new UserModel();
            $userModel = $user_model->getUser($user_id);
            // Vérification de l'ancien mots de passe
            $checked = password_verify($input['last-password'], $userModel->password);
            // Si l'ancien mots de passe est correct et que le nouveau mots de passe identique sur les 2 champs
            if ($checked && htmlspecialchars($input['new-password']) == htmlspecialchars($input['new-password-verify'])) {
                // hashage du password
                $new_password_hash = password_hash(htmlspecialchars($input['new-password']), PASSWORD_DEFAULT, ['cost' => 12]);
                // Modification du mots de passe
                $user_model = new UserModel();
                $success = $user_model->modifyPassword($new_password_hash, $email);
                if (!$success) {
                    throw new \Exception('Un problème est survenu lors de la modification du mot de passe');
                } else {
                    header('Location: index.php');
                }
            }
        }
    }
    /**
     * Page du mots de passe oublié 
     *
     * @return void
     */
    public function forgotPasswordPage(): void
    {
        $user = null;
        $connect = false;
        if (isset($_SESSION['user'])) {
            $user = $_SESSION['user'];
            $connect = true;
        }
        

        echo $this->twig->render("forgot-password.html.twig", ["title" => "Mots de passe oublié", 'user' => $user, 'connect' => $connect]);
    }
    /**
     * Envoie du mots de passe temporaires
     *
     * @return void
     */
    public function sendPassword(): void
    {
        $input = $_POST;
        $to = null;
        $user = null;
        $connect = false;
        if (isset($_SESSION['user'])) {
            $user = $_SESSION['user'];
            $connect = true;
        }
        
        if ($connect) {
            header('Location : index.php');
        } else {
            // Vérifier que l'email sois saisie
            if ($input['input-email'] !== null) {
                $email_input = $input['input-email'];
                // Vérifier que l'utilisateur existe
                $user_model = new UserModel();
                $user = $user_model->checkedUser($email_input);
                if ($user === null) {
                    echo $this->twig->render("forgot-password.html.twig", ["title" => "Mots de passe oublié", 'connect' => $connect, 'error' => true]);
                } else {
                    $to = htmlspecialchars($input['input-email']);
                    // Formatage du nouveau mots de passe
                    $bytes = openssl_random_pseudo_bytes(8);
                    $hex   = bin2hex($bytes);
                    $password_temporary = $hex;
                    $new_password_hash = password_hash($password_temporary, PASSWORD_DEFAULT, ['cost' => 12]);
                    // Modifier le mots de passe actuelle 
                    $user_model = new UserModel();
                    $success = $user_model->modifyPassword($new_password_hash, $email_input);
                    if (!$success) {
                        throw new \Exception('Un problème est survenu lors de la modification du mot de passe');
                    } else {
                        $subject = 'Votre mot de passe temporaire';
                        $message = "Bonjour,\r\nVoici votre mot de passe probatoire : " . $password_temporary . " Nous vous conseillons de le changer immédiatement après votre connexion. http://localhost:8080/Blog/OpenclassroomProject-Blog/index.php?action=login";
                        $headers = "Content-Type: text/plain; charset=utf-8\r\n";
                        $headers .= "From: tom@studio509.fr\r\n";
                        mail($to, $subject, $message, $headers);
                        echo $this->twig->render("login.html.twig", ["title" => "Connexion", 'connect' => $connect]);
                    }
                }
            }
        }
    }
}
