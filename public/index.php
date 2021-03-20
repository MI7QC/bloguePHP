<?php
require '../vendor/autoload.php';

// voir le nombre de ms au chargement page
define('DEBUG_TIME', microtime(true));

//debugueur
//https://packagist.org/packages/filp/whoops
$whoops = new \Whoops\Run;
$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
$whoops->register();

//creation d'un objet de la classe Routeur
$router = new App\Router(dirname(__DIR__) . '/views');

$router
    ->get('/', 'post/index', 'home')
    ->get('/blog/[*:slug]-[i:id]]', 'post/show', 'post') // 3ieme agurment va chercher la vue show qui dans le dossier post 4ieme, arguement nom de l'url'
    ->get('/blog/category', 'category/show', 'category')
    ->run();
