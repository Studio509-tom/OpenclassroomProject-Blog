<?php
namespace Application\Controllers;

use Application\Model\UserModel;

class UserController {
    // affichage de la page d'enregistrement
    public function registerPage( $twig) {
        echo $twig->render("register.html.twig", ['title' => 'Enregistrement']);
    }
    // Traitement du formulaire d'enregistrement
    public function register($input ,$twig) {
        $name = null;
        $firstname = null;
        $email = null;
        $password_hash = null;
        var_dump($input);
        //Vérification si les input ne sont pas vide
        if(!empty($input["input-name"]) && !empty($input["input-firstname"]) && !empty($input["input-email"]) && !empty($input["inputPassword"]) ){
            $name = $input['input-name'];
            $firstname = $input['input-firstname'];
            $email = $input['input-email'];
            //hashage du password
            $password_hash = password_hash($_POST['inputPassword'], PASSWORD_DEFAULT, ['cost' => 12]);
            //Vérifier le password
            // $checked = password_verify($_POST['inputPassword'], $hash);
        }
        
        $userModel = new UserModel();
        $userModel->addUser($name , $firstname , $email , $password_hash);
        var_dump($userModel);

    }

    public function login($twig) {
    
    }
    

}