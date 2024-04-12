<?php

namespace Application;

class ParentController
{
    public $twig;
    public $variables_js;
    public function __construct()
    {
        $loader = new \Twig_Loader_Filesystem(__DIR__ . '/../templates');
        $this->twig = new \Twig_Environment($loader, [
            'cache' => false,
        ]);

        $user = null;
        $connect = false;
        $is_admin = false;
        if (isset($_SESSION['user'])) {
            $user = $_SESSION['user'];;
            $connect = true;
            $is_admin = true;
        }

        $this->twig->addGlobal('user', $user);
        $this->twig->addGlobal('connect', $connect);

        $this->twigAddVariableJS('user', $user, TRUE);
        $this->twigAddVariableJS('connect', $connect, TRUE);
        $this->twigAddVariableJS('is_admin', $is_admin, TRUE);
    }
    
    /**
     * twigAddVariableJS
     *
     * @param  string $name
     * @param  mixed $value
     * @param  bool $json_encode
     * @return void
     */
    public function twigAddVariableJS(string $name,mixed $value,bool $json_encode = TRUE):void
    {
        if ($json_encode) {
            $value = json_encode($value);
        }
        $this->variables_js[] = "<script> var {$name} = {$value}</script>";
        $this->twig->addGlobal('variables_js', $this->variables_js);
    }

}
