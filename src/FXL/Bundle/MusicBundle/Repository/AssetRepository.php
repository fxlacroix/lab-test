<?php

namespace FXL\Bundle\MusicBundle\Repository;

use Doctrine\ORM\AbstractQuery,
    Doctrine\ORM\EntityRepository;

/**
 * AssetRepository
 */
class AssetRepository extends EntityRepository
{

    public function findAllResursive($project=null, $track=null){

        $queryBuilder = $this
            ->createQueryBuilder('a')
            ->select('a, aa')
            ->leftJoin("a.project", "ap")
            ->leftJoin("a.author", "aa")
            ->leftJoin("a.track", "at")
            ->leftJoin("a.article", "aar")

            ->leftJoin("aa.tracks", "aat")
            ->leftJoin("aa.projects", "aap")
            ->leftJoin("at.project", "atp")
            ->leftJoin("aar.project", "aarp")

            ->leftJoin("aat.project", "aatp")

            ->leftJoin("at.project", "atpp")
            ->groupBy("a.id")
            ;

            if($project) {
                $queryBuilder
                    ->where("ap.id =:projectId OR aap.id=:projectId OR aatp.id=:projectId OR atp.id=:projectId OR aarp.id=:projectId")
                    ->setParameter("projectId", $project->getId());
            }

            if($track){
                $queryBuilder
                    ->where("aat.id=:trackId OR at.id=:trackId")
                    ->setParameter("trackId", $track->getId());
            }

//            \Doctrine\Common\Util\Debug::dump($queryBuilder->getQuery()->getArrayResult());

        return $queryBuilder->getQuery()->getArrayResult();

    }
}
