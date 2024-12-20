<?php

namespace Application\Controllers;

use Application\ParentController;

class HomepageController extends ParentController
{

    /**
     * Retourne la page d'accueil
     *
     * @return void
     */
    public function homepage(): void
    {
        $user = null;
        $connect = false;
        if (isset($_SESSION['user'])) {
            $user = $_SESSION['user'];
            $connect = true;
        }
        
        echo $this->twig->render("homepage.html.twig", ['title' => 'Accueil', 'user' => $user, 'connect' => $connect ]);
    }

    /**
     * Retourne la page d'erreur
     *
     * @param  string $errorMessage
     * @return void
     */
    public function errorPage(string $errorMessage): void
    {
        $user = null;
        $connect = false;
        if (isset($_SESSION['user'])) {
            $user = $_SESSION['user'];
            $connect = true;
        }
        echo $this->twig->render('error.html.twig', ['error' => $errorMessage, 'title' => 'Erreur', 'connect' => $connect, 'user' => $user]);
    }

    /**
     * sendMail
     *
     * @return void
     */
    public function sendMail(): void
    {
        $user = null;
        $connect = false;
        if (isset($_SESSION['user'])) {
            $user = $_SESSION['user'];
            $connect = true;
        }
        $mail_recipient = 'tomtfc8200@gmail.com';
        //on vérifie que le champ mail est correctement rempli
        $error_email = false;
        $error_name = false;
        $error_firstname = false;
        $error_message = false;
        $input_email = $_POST['inputEmail'];
        if (empty($input_email)) {
            $error_email = true;
        }
        if (empty($_POST['input-name'])) {
            $error_name = true;
        }
        if (empty($_POST['input-firstname'])) {
            $error_firstname = true;
        }
        if (empty($_POST['inputMessage'])) {
            $error_message = true;
        }
        
        if (!$error_email && !$error_name && !$error_firstname && !$error_message) {
            // Vérification des charactère rentrées
            if (!preg_match("/^[a-zA-Z ]*$/", $_POST["input-name"]) || !preg_match("/^[a-zA-Z ]*$/", $_POST["input-firstname"]) || !preg_match("/^[a-z0-9_-]+((\.[a-z0-9_-]+){1,})?@[a-z0-9_-]+((\.[a-z0-9_-]+){1,})?\.[a-z]{2,}$/i", $_POST['inputEmail'])) {
                echo $this->twig->render('homepage.html.twig', ['title' => 'Accueil', 'success' => false, 'connect' => $connect, 'user' => $user]);
            } else {
                $name = $_POST['input-name'];
                $firstname = $_POST['input-firstname'];
                $user_mail = $_POST['inputEmail'];
                $headers = [];
                $headers[] = 'MIME-Version: 1.0';
                $headers[] = 'Content-type: text/html; charset=UTF-8';
                $headers[] = 'From: Blog <' . $user_mail . '>';
                $headers[] = 'Reply-To: Blog <' . $user_mail . '>';

                //ajoute des sauts de ligne entre chaque headers
                $headers = implode("\r\n", $headers);
                $subject = "Contact";
                $message = htmlspecialchars($_POST['inputMessage']);
                $message = nl2br($message);
                // Envoie du mail
                $success = mail($mail_recipient, $subject, $message, $headers);

                if (!$success) {
                    $errorMessage = error_get_last()['message'];
                    echo $this->twig->render('homepage.html.twig', ['title' => 'Acceuil', 'error' => true, 'connect' => $connect, 'user' => $user]);
                } else {
                    header('Location: index.php?action=confirm-form');
                }
            }
        } else {
            echo $this->twig->render('homepage.html.twig', ['title' => 'Acceuil', 'errorMail' => $error_email, 'errorName' => $error_name, 'errorFirstname' => $error_firstname, 'errorMessage' => $error_message, 'connect' => $connect, 'user' => $user]);
        }
    }

    /**
     * Confirmatin de l'envoie du mail
     *
     * @return void
     */
    public function confirmForm(): void
    {
        $user = null;
        $connect = false;
        if (isset($_SESSION['user'])) {
            $user = $_SESSION['user'];
            $connect = true;
        }
        
        echo $this->twig->render('confirmForm.html.twig', ['title' => 'Confirmation', 'connect' => $connect, 'user' => $user]);
    }

}
