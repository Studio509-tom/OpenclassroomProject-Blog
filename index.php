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

    }
    else{
        (new HomepageController())->homepage($twig);
    }
}catch(\Exception $e){

}