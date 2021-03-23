<?php
require '../vendor/autoload.php';

// voir le nombre de ms au chargement page
define('DEBUG_TIME', microtime(true));

//debugueur
//https://packagist.org/packages/filp/whoops
$whoops = new \Whoops\Run;
$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
$whoops->register();


//si page est recuperer dans le URL et que page=1  
if (isset($_GET['page']) && $_GET['page'] === '1') {
    //$_SERVER['REQUEST_URI] recupere le lien url / explode enleve les ? dans le url et [0] recupere la premier parti du url
    $uri = explode('?', $_SERVER['REQUEST_URI'])[0];
    //créer une copie de $_GET pour la sécurité (imposible de modifier le url get)
    $get = $_GET;

    //retire la clé de ce tableau  la clé page
    unset($get['page']);

    $query = (http_build_query($get));
    // si query est vide l'url on la touche pas
    if (!empty($query)) {
        $uri = $uri . '?' . $query;
    }
    //http_response_code URL rediriger de facon permanent 
    http_response_code(301);
    header('Location: ' . $uri);
    exit();


    // dd($_SERVER); 
}
// si page est strictement === '1' (chaine de caractere)  il est renvoyer sur la page Home
// if ($page === '1') {
//     header('Location: ' . $router->url('home'));
//     http_response_code(301);
//     exit();
// }



//creation d'un objet de la classe Routeur
$router = new App\Router(dirname(__DIR__) . '/views');

$router
    ->get('/', 'post/index', 'home')
    ->get('/blog/category/[*:slug]-[i:id]]', 'category/show', 'category')
    ->get('/blog/[*:slug]-[i:id]]', 'post/show', 'post') // 3ieme agurment va chercher la vue show qui dans le dossier post 4ieme, arguement nom de l'url'
    ->run();
