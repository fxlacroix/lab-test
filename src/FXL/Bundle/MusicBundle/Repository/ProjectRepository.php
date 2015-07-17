<?php

namespace FXL\Bundle\MusicBundle\Repository;

use Doctrine\ORM\AbstractQuery,
    Doctrine\ORM\EntityRepository;

/**
 * ProjectRepository
 */
class ProjectRepository extends EntityRepository
{
    public function findRandomLogo($projectId, $type="logo") {

         $queryBuilder = $this
             ->createQueryBuilder('p')
             ->select("p, assets, t, ta, taa")
             ->leftJoin('p.assets', 'assets')
             ->leftJoin('p.tracks', 't')
             ->leftJoin('t.author', 'ta')
             ->leftJoin('ta.assets', 'taa')
             ->where("p.id = :pid")
             ->setParameter("pid", $projectId)
             ->andWhere("assets.type=:type")
             ->setParameter("type", $type)
         ;

         return $queryBuilder->getQuery()->getArrayResult();
    }


    public function getMaxVersion($project) {

        $queryBuilder = $this
            ->createQueryBuilder('p')
            ->select('max(d.version)')
            ->leftJoin('p.tracks', 't')
            ->leftJoin('t.documents', 'd')
            ->where("p.id = :projectId")
            ->setParameter("projectId", $project->getId());

        return $queryBuilder->getQuery()->getOneOrNullResult();
    }

    public function findLastProjects($notId = null, $groupBy=true, $withId=null)
    {
        $queryBuilder = $this
            ->createQueryBuilder('p')
            ->select('p, pu, pt, a, t, d, assets, asset_author, track_author, asset_track_author, project_author')

            ->leftJoin('p.articles', 'a')

            ->leftJoin("p.tags", "pt")

            ->leftJoin('p.assets', 'assets')
            ->leftJoin('p.tracks', 't')

            ->leftJoin('t.documents', 'd')

            ->leftJoin('t.authors', 'track_author')
            ->leftJoin('p.authors', 'project_author')

            ->leftJoin('project_author.assets', 'asset_author')
            ->leftJoin('track_author.assets', 'asset_track_author')

            ->innerJoin('p.user', 'pu')

            ->where('p.type = :music')
            ->setParameter("music", "music")

            ->groupBy("p.id")
            ->orderBy('d.updatedAt', 'desc')
            ->addOrderBy('track_author.name', 'asc')
            ->addOrderBy('t.number', 'asc')
            ->addOrderBy('t.name', 'asc')
            ->addOrderBy('d.version', 'desc');

        if($withId){
            $queryBuilder->andWhere('p.id =:pid')
                ->setParameter('pid', $withId)
                ;
        }

        if ($groupBy) {
            $queryBuilder->groupBy('p.id');
        }
        if ($notId) {
            $queryBuilder->andWhere("p.id not in (".$notId.")");
        }

        return $queryBuilder->getQuery()->getArrayResult();
    }


    public function findAdminLastProjects($type= null, $projectId = null, $groupBy=true, $user=null)
    {
        $queryBuilder = $this
            ->createQueryBuilder('p')
            ->select('p, pt, a, t, d, assets, asset_author, track_author, asset_track_author, project_author')

/*            ->leftJoin('p.acts', 'pact')
            ->leftJoin('pact.scenes', 'actScene')
            ->leftJoin('pact.actors', 'actActors')
*/
            ->leftJoin('p.articles', 'a')

            ->leftJoin("p.tags", "pt")

            ->leftJoin('p.assets', 'assets')
            ->leftJoin('p.tracks', 't')

            ->leftJoin('t.documents', 'd')

            ->leftJoin('t.authors', 'track_author')
            ->leftJoin('p.authors', 'project_author')

            ->leftJoin('project_author.assets', 'asset_author')
            ->leftJoin('track_author.assets', 'asset_track_author')

            ->orderBy('d.updatedAt', 'desc')
            ->addOrderBy('track_author.name', 'asc')
            ->addOrderBy('t.number', 'asc')
            ->addOrderBy('t.name', 'asc')
            ->addOrderBy('d.version', 'desc');

        if($user){

          $queryBuilder
            ->leftJoin('p.user', 'pu')
            ->andWhere('pu.id = :user')
            ->setParameter('user', $user->getId());
        }

        if($type) {

            $queryBuilder->andWhere('p.type = :type')
            ->setParameter("type", $type);
        }

        if ($groupBy) {
            $queryBuilder->groupBy('p.id');
        }

        if ($projectId) {

            $queryBuilder->andWhere('p.id =:pid')
                ->setParameter('pid', $projectId)
                ;
        }

        return $queryBuilder->getQuery()->getResult();
    }

}
