<?php

namespace FXL\Bundle\MusicBundle\Repository;

use Doctrine\ORM\AbstractQuery,
    Doctrine\ORM\EntityRepository;

/**
 * DocumentRepository
 */
class DocumentRepository extends EntityRepository
{

    public function findLastDocuments($limit=null)
    {
        $queryBuilder = $this
            ->createQueryBuilder('d')
            ->select('d, t, p, tags, pu ')
            ->leftJoin('d.track', 't')
            ->leftJoin('t.project', 'p')
            ->leftJoin('p.tags', 'tags')
            ->innerJoin('p.user', 'pu')
            ->where('p.type = :music')
            ->setParameter("music", "music")
            ->orderBy('d.updatedAt', 'desc')
            ->addOrderBy('tags.name', 'asc')
            ->groupBy("d.id")
            ;

        if ($limit) {

            $queryBuilder = $queryBuilder->setMaxResults($limit);
        }

        return $queryBuilder->getQuery()->getArrayResult();
    }

}
