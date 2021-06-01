<?php

//appelle la Classe Text
use App\Connection;
use App\URL;
use App\Model\Post;
use App\Helpers\Text;
use APP\PaginatedQuery;


$title = 'Mon Blog';
//CONNECTION BDD -> appelle class Connection et function getPDO()
$pdo = Connection::getPDO();

$pagionatedQuery = new PaginatedQuery(
    "SELECT * FROM post ORDER BY created_at DESC",
    "SELECT COUNT(id) FROM post",
    Post::class
);
$posts = $pagionatedQuery->getItems();
$link = $router->url('home');
?>

<h1>Mon Blog</h1>

<?php //dd($posts); 
?>

<div class="row">
    <?php foreach ($posts as $post) : ?>
        <div class="col-md-3">
            <?php require 'card.php' ?>
        </div>
    <?php endforeach ?>
</div>


<!-- Pagination-->
<div class="d-flex justify-content-between my-4">
    <?= $PaginatedQuery->previousLink($link) ?>
    <?= $PaginatedQuery->nextLink($link) ?>
</div>