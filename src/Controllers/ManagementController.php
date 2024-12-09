<?php

namespace Application\Controllers;

use Application\ParentController;
use Application\Model\UserModel;

class ManagementController extends ParentController
{
    /**
     * Retourne la page de gestion 
     *
     * @return void
     */
    public function managementPage(): void
    {
        $user = null;
        $connect = false;
        if (isset($_SESSION['user'])) {
            $user = $_SESSION['user'];
            $connect = true;
        }
        
        $userModel = new UserModel();
        if ($user !== null) {
            // Vérification si l'user à le role admin
            if ($user->isAdmin()) {
                echo $this->twig->render("management.html.twig", ["title" => "Gestion du site", "user" => $user, 'connect' => $connect]);
            } else {
                throw new \Exception("Vous n'êtes pas autorisé acceder à cette page");
            }
        } else {
            throw new \Exception("Vous n'êtes pas autorisé acceder à cette page");
        }
    }
}
