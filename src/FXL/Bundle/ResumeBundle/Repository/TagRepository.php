<?php

namespace FXL\Bundle\ResumeBundle\Repository;

use Doctrine\ORM\EntityRepository;

class TagRepository extends EntityRepository
{

    public function findTagsWithSkills(){

        $qb = $this->createQueryBuilder('t')
            ->groupBy('t.id')
            ->orderBy('t.weight', 'desc')
            ->innerJoin('t.skills', 'ts');

        $qb->getQuery()->getSQL();

        return $qb
            ->getQuery()
            ->getResult()
            ;
    }
}
