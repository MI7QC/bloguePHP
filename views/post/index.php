<?php

//appelle la Classe Text
use App\Connection;
use App\URL;
use App\Model\Post;
use App\Helpers\Text;


$title = 'Mon Blog';
//CONNECTION BDD -> appelle class Connection et function getPDO()
$pdo = Connection::getPDO();


//appelle la class URL methode(funtion) getInt
$currentPage = URL::getPositiveInt('page', 1);

//Recupere le nombre d'article(post) et converti  FETCH_NUM = tableau numérique et (int) = entier et non une chaine de caractere
$count = (int)$pdo->query('SELECT COUNT(id) FROM post')->fetch(PDO::FETCH_NUM)[0];
$perPage = 12;
// $count nombre d'article divisé par $perPage = 12   Ceil arrondi au nombre supérieur
$pages = ceil($count / $perPage);
//si la page actuel est supérieur au nombre de page = erreur
if ($currentPage > $pages) {
    throw new Exception('cette page n\'existe pas');
}

// prend le nombre d'élement par page et le multipli par la page currente $currentPage
$offset = $perPage * ($currentPage - 1);
// recupere les 12 dernier resultat
$query = $pdo->query("SELECT * FROM post ORDER BY created_at DESC LIMIT $perPage OFFSET $offset");
// APELLE la Class Post
$posts = $query->fetchAll(PDO::FETCH_CLASS, Post::class);



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
    <!-- Page precedente  Affiché boutton si $currentPage > 1-->
    <?php if ($currentPage > 1) : ?>
        <?php
        $link = $router->url('home');
        if ($currentPage > 2) $link .= '?page=' . ($currentPage - 1);
        ?>
        <a href="<?= $link ?>" class="btn btn-primary">&laquo; Page précédente</a>
    <?php endif ?>

    <!-- si on est inférieur au nombre de page sa affichera -->
    <?php if ($currentPage <  $pages) : ?>
        <a href=""></a> <!-- triche pour que lautre bouton soit a droit ml-auto-->
        <a href="<?= $router->url('home') ?>?page=<?= $currentPage + 1 ?>" class="btn btn-primary ml-auto">Page suivante &raquo;</a>
    <?php endif ?>
</div>