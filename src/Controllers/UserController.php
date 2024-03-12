<?php

namespace Application\Controllers;

use Application\ParentController;

use Application\Model\UserModel;

class UserController extends ParentController
{
    // affichage de la page d'enregistrement
    public function registerPage()
    {
        $twig = $this->twig;
        echo $twig->render("register.html.twig", ['title' => 'Enregistrement']);
    }
    public function loginPage()
    {
        $twig = $this->twig;
        echo $twig->render("login.html.twig", ['title' => 'Enregistrement']);
    }
    // Traitement du formulaire d'enregistrement
    public function register($input)
    {
        $name = null;
        $firstname = null;
        $email = null;
        $password_hash = null;
        //Vérification si les input ne sont pas vide
        if (!empty($input["input-name"]) && !empty($input["input-firstname"]) && !empty($input["input-email"]) && !empty($input["inputPassword"])) {
            if (!preg_match("/^[a-zA-Z ]*$/", $input["input-name"]) || !preg_match("/^[a-zA-Z ]*$/", $input["input-firstname"]) || !preg_match("/^[a-zA-Z0-9][^\s\<\>]*$/", $input["input-email"])) { //si c'est pas un mot
                echo $this->twig->render("register.html.twig", ['title' => 'Enregistrement', 'name' => $input["input-name"], 'firstname' => $input["input-firstname"], 'email' => $input["input-email"], 'password' => $input["inputPassword"], 'hacking' => true]);
            } else { //ici dernière condition optionnelle pour montrer que si ce n'est pas un nombre
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
            $email_check = $userModel->getUser($email);
            // Si email n'est pas en base de données
            if ($email_check == null) {
                $userModel->addUser($name, $firstname, $email, $password_hash);
                $user = $userModel->getUser($email);
                $user->password = null;
                $_SESSION['user'] = $user;
                header('Location: index.php');

                echo $this->twig->render("homepage.html.twig", ['title' => 'Accueil', "user" => $user, 'connect' => true]);
            } else {
                $twig = $this->twig;
                echo $twig->render("register.html.twig", ['title' => 'Enregistrement', 'name' => $input["input-name"], 'firstname' => $input["input-firstname"], 'email' => $input["input-email"], 'password' => $input["inputPassword"], 'exist' => true]);
            }
        } else {
            $twig = $this->twig;
            echo $twig->render("register.html.twig", ['title' => 'Enregistrement', 'name' => $input["input-name"], 'firstname' => $input["input-firstname"], 'email' => $input["input-email"], 'password' => $input["inputPassword"], 'error' => true]);
        }
    }
    // Vérification de connexion
    public function login($input)
    {
        $email = null;
        if (!empty($input["input-email"]) && !empty($input["inputPassword"])) {
            if (!preg_match("/^[a-zA-Z0-9][^\s\<\>]*$/", $input["input-email"])) { //si c'est pas un mot
                echo $this->twig->render("login.html.twig", ['title' => 'Connexion', 'error' => true]);
            } else {
                $email = $input['input-email'];
                $user_model = new UserModel();
                $user = $user_model->getUser($email);

                if ($user == null) {
                    echo $this->twig->render("login.html.twig", ['title' => 'Connexion', 'error' => true]);
                } else {
                    $checked = password_verify($_POST['inputPassword'], $user->password);
                    if ($checked) {
                        $user->password = null;
                        $_SESSION['user'] = $user;
                        header('Location: index.php');
                    }
                }
            }
        } else {
            echo $this->twig->render("login.html.twig", ['title' => 'Connexion', 'error' => true]);
        }
    }
}
