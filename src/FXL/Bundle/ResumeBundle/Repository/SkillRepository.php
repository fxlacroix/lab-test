<?php

namespace FXL\Bundle\ResumeBundle\Repository;

use Doctrine\ORM\EntityRepository;

class SkillRepository extends EntityRepository
{

    public function findSkillsGroupByTags(){

        $qb = $this->createQueryBuilder('s')
            ->orderBy('st.weight', 'desc')
            ->innerJoin('s.tags', 'st');

        $skills = $qb->getQuery()->getResult();

        $tags = array();
        foreach($skills as $skill){
            foreach($skill->getTags() as $tag){
                $tags[$tag->getId()][] = $skill;
            }
        }

        return $tags;
    }
}
