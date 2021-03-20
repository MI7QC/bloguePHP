<?php

namespace APP\Model;

use App\Helpers\Text;
use DateTime;

class Post
{

    private $id;
    private $slug;
    private $name;
    private $content;
    private $created_at;
    private $categories = [];

    //Getteur qui recupere le nom qui est private
    public function getName(): ?string
    {
        return $this->name;
    }

    public function getExcerpt(): ?string
    {
        if ($this->content === null) {
            return null;
        }
        //htmlentities ne peut pas entré du code HTML (sécuritaire ) et nl2br permet de faire le saut de ligne 
        return nl2br(htmlentities(Text::excerpt($this->content, 60)));
    }

    //Getteur converti le created_at en DateTime
    public function getCreatedAt(): DateTime
    {
        return new DateTime($this->created_at);
    }

    //Getteur 
    public function getSlug(): ?string
    {
        return $this->slug;
    }

    //Getteur 
    public function getID(): ?int
    {
        return $this->id;
    }
}
