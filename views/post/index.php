<?php

//appelle la Classe Text
use App\Helpers\Text;
use App\Model\Post;

$title = 'Mon Blog';
//CONNECTION BDD
$servername = "127.0.0.1";
$username = "root";
$password = "";

try {
    $pdo = new PDO("mysql:host=$servername;dbname=tutoblog", $username, $password);
    // set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connected successfully";
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

// recupere les 12 dernier resultat
$query = $pdo->query('SELECT * FROM post ORDER BY created_at DESC LIMIT 12');
$posts = $query->fetchAll(PDO::FETCH_CLASS, Post::class);  // APELLE la Class Post
// $posts = $query->fetchAll(PDO::FETCH_OBJ);   peut caller un OBJ


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