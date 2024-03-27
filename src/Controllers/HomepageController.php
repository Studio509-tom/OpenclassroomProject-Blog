<?php

namespace Application\Controllers;

use Application\ParentController;

class HomepageController extends ParentController
{
        
    /**
     * Retourne la page d'accueil
     *
     * @param mixed $session_user 
     * @return void
     */
    public function homepage(mixed $session_user)
    {
        $user = null;
        $connect = false;
        if ($session_user !== null) {
            $user = $session_user;
            $connect = true;
        }
        echo $this->twig->render("homepage.html.twig", ['title' => 'Accueil', 'user' => $user, 'connect' => $connect]);
        

    }
    
    /**
     * errorPage
     *
     * @param  mixed $session_user
     * @param  string $errorMessage
     * @return void
     */
    public function errorPage(mixed $session_user ,string $errorMessage):void
    {
        $user = null;
        $connect = false;
        if ($session_user !== null) {
            $user = $session_user;
            $connect = true;
        }
        echo $this->twig->render('error.html.twig', [ 'error'=>$errorMessage , 'title' => 'Erreur' , 'connect' => $connect, 'user' => $user]); 

    }
}
