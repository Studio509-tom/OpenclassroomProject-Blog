<?php

namespace Application\Controllers;

use Application\ParentController;

class HomepageController extends ParentController
{

    public function homepage($session_user)
    {
        $user = null;
        $connect = false;
        if ($session_user !== null) {
            $user = $session_user;
            $connect = true;
        }
        echo $this->twig->render("homepage.html.twig", ['title' => 'Accueil', 'user' => $user, 'connect' => $connect]);
        

    }
}
