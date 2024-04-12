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

        global $session_user;
        $user = null;
        $connect = false;
        $is_admin = false;
        if ($session_user !== null) {
            $user = $session_user;
            $connect = true;
            $is_admin = true;
        }

        $this->twig->addGlobal('user', $user);
        $this->twig->addGlobal('connect', $connect);

        $this->twigAddVariableJS('user', $user, TRUE);
        $this->twigAddVariableJS('connect', $connect, TRUE);
        $this->twigAddVariableJS('is_admin', $is_admin, TRUE);
    }

    public function twigAddVariableJS($name, $value, $json_encode = TRUE)
    {
        if ($json_encode) {
            $value = json_encode($value);
        }
        $this->variables_js[] = "<script> var {$name} = {$value}</script>";
        $this->twig->addGlobal('variables_js', $this->variables_js);
    }

    // public function get_javascript($connect, $user)
    // {
    //     echo "<script> var connect = " . json_encode($connect) . "</script>";
    //     if (!is_null($user)) {
    //         if ($user->role == "admin") {
    //             echo "<script> var is_admin = " . json_encode($user->role) . "</script>";
    //         }
    //     } else {
    //         echo "<script> var is_admin = " . json_encode(false) . "</script>";
    //     }
    // }
}
