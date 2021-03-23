<?php

// dd($params);

use App\Connection;
use App\Model\Category;
use App\Model\Post;
//recupere l'id et converti en int et le slug de l'url
$id = (int)$params['id'];
$slug = $params['slug'];

// dd($id, $slug);

// appelle la class Connection et methode getPDO()
$pdo = Connection::getPDO();
// prepare la requet via  la BDD
$query = $pdo->prepare('SELECT * FROM post WHERE id = :id');
// execute la requet mysql $query  et entre en parametre l'id du url
$query->execute(['id' => $id]);
$query->setFetchMode(PDO::FETCH_CLASS, POST::class);
//fetch recupere une ligne
$post = $query->fetch();

/** @var Post|false */
if ($post === false) {
    throw new Exception('Aucun article ne correspond à cet ID');
}

// si le slug de l'article est different du slug dans l'url
//fait une redirection
if ($post->getSlug() !== $slug) {
    $url = $router->url('post', ['slug' => $post->getSlug(), 'id' => $id]);
    http_response_code(301);
    header('Location: ' . $url);
}

$query = $pdo->prepare('SELECT c.id, c.slug , c.name
FROM post_category pc 
JOIN category c ON pc.category_id = c.id
WHERE pc.post_id = :id');
$query->execute(['id' => $post->getId()]);
// recupere les information sous forme de CLASS  PDO::FETCH_CLASS, de la class Category
$query->setFetchMode(PDO::FETCH_CLASS, Category::class);
/** @var Category[] */
$categories = $query->fetchAll();
// dd($categories);

?>


<h1><?= e($post->getName()) ?> </h1>
//affiche la date
<p class="test-muted"><?= $post->getCreatedAt()->format('d F Y') ?></p>
<?php foreach ($categories as $category) : ?>
    <a href="<?= $router->url('category', ['id' => $category->getID(), 'slug' => $category->getSlug()]) ?>"><?= e($category->getName()) ?></a>
<?php endforeach ?>
<p><?= $post->getFormattedContent() ?></p>