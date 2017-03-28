<?php

namespace DeviseBundle\Repository;

/**
 * DeviseRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class DeviseRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * Récupère la liste des devises
     * @return array
     */
    public function getListe()
    {
        return $this->createQueryBuilder('d')
            ->select('d')
            ->orderBy('d.label')
            ->getQuery()
            ->getResult();
    }
}
