<?php
require_once("./vendor/autoload.php");

// $loader = new Twig_Loader_Filesystem(__DIR__ .'/templates');
// $twig = new Twig_Environment($loader , [
//     'cache'=> false,
// ]);

spl_autoload_register(function($fqcn){
    $path = str_replace(['Application','\\'],['src' , '/'] , $fqcn) . '.php';
    require_once($path);
});
use Application\Controllers\HomepageController;
use Application\Controllers\UserController;
// var_dump($_GET['action']);
// var_dump($_GET);

try{
    if (isset($_GET['action']) && $_GET['action'] !== ''){
        // var_dump($_GET);
        if ($_GET['action'] === 'formRegister') {
            $input = null;
            if($_SERVER['REQUEST_METHOD'] ==='POST'){
                $input = $_POST;
            }
            (new UserController())->register($input);
           
        }    
        elseif ($_GET['action'] === 'register') {
            (new UserController())->registerPage();
        }
        elseif ($_GET['action'] === 'login') {
            (new UserController())->loginPage();
        }
        elseif ($_GET['action'] === 'formLogin'){
            $input = null;
            if($_SERVER['REQUEST_METHOD'] ==='POST'){
                $input = $_POST;
            }
            (new UserController())->login($input);
        }
        else{
            throw new Exception("La page que vous recherchez n'existe pas.");
        }
    }
    else{
        (new HomepageController())->homepage();
    }
}catch(Exception $e){
    $errorMessage = $e->getMessage();
    echo $errorMessage;
    // echo $twig->render('error.html.twig', [ 'error'=>$errorMessage , 'title' => 'Erreur']); 
}