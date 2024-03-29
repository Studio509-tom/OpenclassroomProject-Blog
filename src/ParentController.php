<?php

namespace Application;

class ParentController
{
    public $twig;
    public function __construct()
    {
        $loader = new \Twig_Loader_Filesystem(__DIR__ . '/../templates');
        $this->twig = new \Twig_Environment($loader, [
            'cache' => false,
        ]);
    }
}
