<?php

namespace Application\Controllers;

use Application\ParentController;
use Application\Model\UserModel;

class ManagementController extends ParentController
{
    /**
     * managementPage
     *
     * @param  mixed $session_user
     * @return void
     */
    public function managementPage(mixed $session_user): void
    {
        $user = null;
        $connect = false;
        if ($session_user !== null) {
            $user = $session_user;
            $connect = true;
        }
        $userModel = new UserModel();
        if ($user !== null) {
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
