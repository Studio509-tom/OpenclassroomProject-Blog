<?php

namespace Application\Controllers;

use Application\ParentController;

class HomepageController extends ParentController
{

    public function homepage()
    {
        echo $this->twig->render("homepage.html.twig", ['title' => 'Accueil']);
    }
}
