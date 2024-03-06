<?php
namespace Application\Controllers;

class HomepageController
{
    public function homepage($twig){
       echo $twig->render("homepage.html.twig");
    }
}