<?php

namespace App\Model;

class Category
{

    private $id;

    private $slug;

    private $name;

    // getteur getID
    public function getID(): ?int
    {
        return $this->id;
    }
    // getteur getSlug
    public function getSlug(): ?string
    {
        return $this->slug;
    }

    // getteur getName
    public function getName(): ?string
    {
        return $this->name;
    }
}
