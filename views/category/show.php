<?php

use App\Connection;
use App\Model\{Category, Post};
use App\URL;

//recupere l'id et converti en int et le slug de l'url
$id = (int)$params['id'];
$slug = $params['slug'];

// dd($id, $slug);

// appelle la class Connection et methode getPDO()
$pdo = Connection::getPDO();
// prepare la requet via  la BDD
$query = $pdo->prepare('SELECT * FROM category WHERE id = :id');
// execute la requet mysql $query  et entre en parametre l'id du url
$query->execute(['id' => $id]);
$query->setFetchMode(PDO::FETCH_CLASS, Category::class);
//fetch recupere une ligne
$category = $query->fetch();

/** @var category|false */
if ($category === false) {
    throw new Exception('Aucun article ne correspond à cet ID');
}

// si le slug de l'article est different du slug dans l'url
//fait une redirection
if ($category->getSlug() !== $slug) {
    $url = $router->url('category', ['slug' => $category->getSlug(), 'id' => $id]);
    http_response_code(301);
    header('Location: ' . $url);
}

// dd($category);

$title = "Catégorie {$category->getName()}";



//appelle la class URL methode(funtion) getInt
$currentPage = URL::getPositiveInt('page', 1);
//Recupere le nombre d'article(post) et converti  FETCH_NUM = tableau numérique et (int) = entier et non une chaine de caractere
$count = (int)$pdo->query('SELECT COUNT(category_id) FROM post_category WHERE category_id = ' . $category->getID())
    ->fetch(PDO::FETCH_NUM)[0];
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
$query = $pdo->query("
SELECT p.* 
FROM post p
JOIN post_category pc ON pc.post_id = p.id
WHERE pc.category_id = {$category->getID()}
ORDER BY created_at DESC 
LIMIT $perPage OFFSET $offset
");
// APELLE la Class Post
$posts = $query->fetchAll(PDO::FETCH_CLASS, Post::class);
// recupere la route cartegory quon lui passe l'is de la category + le slug
$link = $router->url('category', ['id' => $category->getID(), 'slug' => $category->getSlug()]);





?>

<h1><?= e($title) ?></h1>


<div class="row">
    <?php foreach ($posts as $post) : ?>
        <div class="col-md-3">
            <?php require dirname(__DIR__) . '/post/card.php' ?>
        </div>
    <?php endforeach ?>
</div>


<!-- Pagination-->
<div class="d-flex justify-content-between my-4">
    <!-- Page precedente  Affiché boutton si $currentPage > 1-->
    <?php if ($currentPage > 1) : ?>
        <?php
        if ($currentPage > 2) $link .= '?page=' . ($currentPage - 1);
        ?>
        <a href="<?= $link ?>" class="btn btn-primary">&laquo; Page précédente</a>
    <?php endif ?>

    <!-- si on est inférieur au nombre de page sa affichera -->
    <?php if ($currentPage <  $pages) : ?>
        <a href=""></a> <!-- triche pour que lautre bouton soit a droit ml-auto-->
        <a href="<?= $link ?>?page=<?= $currentPage + 1 ?>" class="btn btn-primary ml-auto">Page suivante &raquo;</a>
    <?php endif ?>
</div>