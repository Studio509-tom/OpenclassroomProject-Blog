<?php

require_once("./vendor/autoload.php");

$loader = new Twig_Loader_Filesystem(__DIR__ .'/templates');
$twig = new Twig_Environment($loader , [
    'cache'=> false,
]);

spl_autoload_register(function($fqcn){
    $path = str_replace(['Application','\\'],['src' , '/'] , $fqcn) . '.php';
    require_once($path);
});

use Application\Controllers\HomepageController;


try{
    if (isset($_GET['action']) && $_GET['action'] !== ''){
        if ($_GET['action'] === 'post') {
           
        }    
        
        else {
            throw new Exception("La page que vous recherchez n'existe pas.");
        }
    }
    else{
        (new HomepageController())->homepage($twig);
    }
}catch(Exception $e){
    echo'tototo';
    $errorMessage = $e->getMessage();
    echo $twig->render('error.html.twig', [ 'error'=>$errorMessage , 'title' => 'Erreur']); 
}