<?php


namespace App\Service;

use Doctrine\Common\Persistence\ObjectManager;

class Pagination
{
    private $entityClass;
    private $limit = 10;
    private $currentPage = 1;
    private $manager;

    // Quand Symfony voudra construire une instance de Pagination, il va automatiquement avoir ObjectManager dans toute la classe
        // $manager à une fonction getRepository(<nom de l'entité>)
    public function __construct(ObjectManager $manager)
    {
        $this->manager = $manager;
    }

    public function getPages() {
        // 1. Connaitre le total d'enregistrement
        $repository = $this->manager->getRepository($this->entityClass);
        $total = count($repository->findAll());

        // 2. Faire la division et l'arrondis et le renvoyer
        $pages = ceil(($total <= 0 ? $total = 1 : $total) / $this->limit);

        return $pages;
    }

    public function getData() {
        // 1. Calculer l'offset (valeur de départ)
            // Si je suis sur la page 1: 1 * 10 - 10 = 0
            // Si je suis sur la page 2: 2 * 10 - 10 = 10

        $offset = $this->currentPage * $this->limit - $this->limit;

        // 2. Demander au Repository de trouver les éléments
        $repository = $this->manager->getRepository($this->entityClass);
        $data = $repository->findBy([], [], $this->limit, $offset);

        // 3. Envoyer les éléments en question
        return $data;
    }


    public function setCurrentPage(int $currentPage)
    {
        $this->currentPage = $currentPage;

        return $this;
    }

    public function getCurrentPage(): int
    {
        return $this->currentPage;
    }

    public function setLimit(int $limit)
    {
        $this->limit = $limit;

        return $this;
    }

    public function getLimit(): int
    {
        return $this->limit;
    }

    public function setEntityClass($entityClass)
    {
        $this->entityClass = $entityClass;

        return $this;
    }

    public function getEntityClass()
    {
        return $this->entityClass;
    }
}