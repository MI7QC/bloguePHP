<?php

require  dirname(__DIR__) . '/vendor/autoload.php';

// use the factory to create a Faker\Generator instance : https://packagist.org/packages/fzaninotto/faker
$faker = Faker\Factory::create('fr_FR');

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

//VIDE LA BDD
//Permet d'ignorer les jointures
$pdo->exec('SET FOREIGN_KEY_CHECKS = 0');
//premier table(post_category) qu'on doit Truncate, parce quel contient les enrigistrements de liaisson
$pdo->exec('TRUNCATE TABLE post_category');
$pdo->exec('TRUNCATE TABLE post');
$pdo->exec('TRUNCATE TABLE category');
$pdo->exec('TRUNCATE TABLE users');
// Reactive les jointures
$pdo->exec('SET FOREIGN_KEY_CHECKS = 1');

//Tous les id de post(article)
$posts = [];
$categories = [];

for ($i = 0; $i < 50; $i++) {
    $pdo->exec("INSERT INTO post SET name='{$faker->sentence()}', slug='{$faker->slug}', created_at='{$faker->date} {$faker->time}', content='{$faker->paragraphs(rand(3, 15), true)}' ");
    //recupere le dernier id et l'insert dans le tableau
    $posts[] = $pdo->lastInsertId();
}

for ($i = 0; $i < 5; $i++) {
    $pdo->exec("INSERT INTO category SET name='{$faker->sentence(3)}', slug='{$faker->slug}'");
    $categories[] = $pdo->lastInsertId();
}


foreach ($posts as $post) {
    // remplis un tableau avec des donnée aleatoire.
    $randomCategories = $faker->randomElements($categories, rand(0, count($categories)));
    //pour chaque category aleatoire il va faire un INSERT
    foreach ($randomCategories as $category) {
        $pdo->exec("INSERT INTO post_category SET post_id=$post, category_id=$category");
    }
}



$password = password_hash('admin', PASSWORD_BCRYPT);
$pdo->exec("INSERT INTO users SET username='admin', password='$password'");
