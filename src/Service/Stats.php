<?php


namespace App\Service;


use Doctrine\Common\Persistence\ObjectManager;

class Stats
{
    private $manager;

    // Pour que l'ObjectManager soit accessible dans toute la classe via la propriété $manager
    public function __construct(ObjectManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * Retourne les derniers éléments
     * @param $letter
     * @param $entity
     * @param int $limit
     * @return mixed
     */
    public function getLast($entity, $limit = 5) {
        return $this->manager
            ->createQuery('SELECT ' . $entity . " FROM App\Entity\\" . ucfirst($entity) . ' ' . $entity . ' ORDER BY ' . $entity . '.id DESC')
            ->setMaxResults($limit)
            ->getResult();
    }


    /**
     * Retourne le nombre total d'éléments
     * @param $letter
     * @param $entity
     * @return mixed
     */
    public function getCount($entity) {
        return $this->manager->createQuery('SELECT COUNT(' . $entity . ') FROM App\Entity\\' . ucfirst($entity) . ' ' . $entity)->getSingleScalarResult();
    }

}