<?php

namespace App;

class Router
{

    /*
    *@var string 
    */
    private $viewPath;

    /*
    *@var AltoRouter 
    */
    private $router;

    public function __construct(string $viewPath)
    {
        $this->viewPath = $viewPath;
        $this->router = new \AltoRouter();
    }

    public function get(string $url, string $view, ?string $name = null): self
    {
        $this->router->map('GET', $url, $view, $name);

        return $this;
    }

    public function url(string $name, array $parems = [])
    {
        return $this->router->generate($name, $parems);
    }

    public function run(): self
    {
        $match = $this->router->match();
        //recupere la vue via le url
        $view = $match['target'];
        //sauvegarde les parametre de l'url 
        $params = $match['params'];
        $router = $this; // reference a function url
        ob_start();
        require $this->viewPath . DIRECTORY_SEPARATOR . $view . '.php';
        $content = ob_get_clean();
        require $this->viewPath . DIRECTORY_SEPARATOR  . 'layouts/default.php';


        return $this;
    }
}
