<?php

namespace APP;

use App\URL;
use \PDO;

class PaginatedQuery
{

    private $query;
    private $queryCount;
    private $classMapping;
    private $pdo;
    private $perPage;
    private $count;

    public function __construct(
        string $query,
        string $queryCount,
        string $classMapping,
        ?\PDO $pdo = null,
        int $perPage = 12
    ) {
        $this->query = $query;
        $this->queryCount = $queryCount;
        $this->classMapping = $classMapping;
        $this->pdo = $pdo ?: Connection::getPDO();
        $this->perPage = $perPage;
    }

    public function getItems(): array
    {
        //appelle la class URL methode(funtion) getInt
        $currentPage = $this->getCurrentPage();
        $pages = $this->getPages();
        //si la page actuel est supérieur au nombre de page = erreur
        if ($currentPage > $pages) {
            throw new Exception('Cette page n\'existe pas');
        }
        // prend le nombre d'élement par page et le multipli par la page currente $currentPage
        $offset = $this->perPage * ($currentPage - 1);

        // recupere les 12 dernier resultat
        return $this->pdo->query(
            $this->query .
                " LIMIT {$this->perPage} OFFSET $offset"
        )->fetchAll(PDO::FETCH_CLASS, $this->classMapping);
    }

    public function previousLink(string $link): ?string
    {
        $currentPage = $this->getCurrentPage();
        $pages = $this->getPages();
        if ($currentPage >= $pages) return null;
        if ($currentPage > 2) $link .= "?page=" . ($currentPage - 1);
        return <<<HTML
        <a href="{$link}" class="btn btn-primary">&laquo; Page précédente</a>
        HTML;
    }

    public function nextLink(string $link): ?string
    {
        $currentPage = $this->getCurrentPage();
        if ($currentPage <= 1) return null;
        if ($currentPage > 2) $link .= "?page=" . ($currentPage - 1);
        $link .= "?page=" . ($currentPage + 1);
        return <<<HTML
        <a href="{$link}" class="btn btn-primary">&laquo; Page précédente</a>
        HTML;
    }

    private function getCurrentPage(): int
    {
        return URL::getPositiveInt('page', 1);
    }


    private function getPages(): int
    {
        if ($this->count === null)
        {
            //Recupere le nombre d'article(post) et converti  FETCH_NUM = tableau numérique et (int) = entier et non une chaine de caractere
            $this->count = (int)$this->pdo
                ->query($this->queryCount)
                ->fetch(PDO::FETCH_NUM)[0];
            }
            // $count nombre d'article divisé par $perPage = 12   Ceil arrondi au nombre supérieur
            return ceil($this->count / $this->perPage);
    }
}
