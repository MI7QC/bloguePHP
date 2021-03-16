<?php
require '../vendor/autoload.php';

$whoops = new \Whoops\Run;
$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
$whoops->register();


$router = new App­\Router(dirname(__DIR__) . '/views');

print_r($router);

$router
->get('/blog', 'post/index', 'blog')
->get('/blog/category', 'category/show', 'category')
->run();