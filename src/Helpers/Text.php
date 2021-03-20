<?php

namespace App\Helpers;

class Text
{
    /**
     *  Function qui consite a couper une chaine de caractere
     */
    public static function excerpt(string $content, int $limit = 60)
    {
        //mb_strlen pour les chaine caractere unicode 
        if (mb_strlen($content) <= $limit) {
            return $content;
        }
        // couper la chaine de caractere commence a 0 fini a $limit = 60
        return substr($content, 0, $limit) . '...';
    }
}
