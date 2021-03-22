<?php

namespace App;

class URL
{

    public static function getInt(string $name, ?int $default = null): ?int
    {

        // verifie si get  page existe
        if (!isset($_GET[$name])) return $default;
        // si $name est une chaine de caractere de 0 on return 0
        if ($_GET[$name] === '0') return 0;
        //validation si c'est un INT sinon renvoit une erreur //Recuperer le parametre dans le URL la clé page. 
        if (!filter_var($_GET[$name], FILTER_VALIDATE_INT)) {
            throw new \Exception("Le paramètre  $name n'est pas un entier");
        }
        return (int)$_GET[$name];
    }

    public static function getPositiveInt(string $name, ?int $default = null): ?int
    {

        //apelle function getInt   self sert a appeller une fonction dans les  class
        $param = self::getInt($name, $default);
        // si param est diferent de null et plus petit ou = a 0
        if ($param !== null && $param <= 0) {
            throw new \Exception("Le paramètre  $name n'est pas un entier positif");
        }
        return $param;
    }
}
