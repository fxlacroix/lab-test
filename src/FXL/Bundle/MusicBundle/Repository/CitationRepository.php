<?php

namespace FXL\Bundle\MusicBundle\Repository;

use Doctrine\ORM\AbstractQuery,
    Doctrine\ORM\EntityRepository;

/**
 * Citation
 */
class CitationRepository extends EntityRepository
{

    public function findOneByRandom()
    {
        $queryBuilder = $this
            ->createQueryBuilder('c')
            ->select('c.id')
            ->where("c.author != null")
            ;

        return $queryBuilder->getQuery()->getOneOrNullResult();
    }

}


?>
