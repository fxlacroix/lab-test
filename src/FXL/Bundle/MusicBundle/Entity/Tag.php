<?php

namespace FXL\Bundle\MusicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="FXL\Bundle\MusicBundle\Repository\TagRepository")
 * @ORM\Table(name="music__tag")
 */
class Tag extends BaseObject
{
    /**
     * @ORM\ManyToMany(targetEntity="Track", inversedBy="tags")
     * @ORM\JoinTable(name="music__tag_track")
     */
    private $tracks;

    /**
     * @ORM\ManyToMany(targetEntity="Project", inversedBy="tags")
     * @ORM\JoinTable(name="music__tag_project")
     */
    protected $projects;

    /**
     * @ORM\ManyToMany(targetEntity="Article", inversedBy="tags")
     * @ORM\JoinTable(name="music__tag_article")
     */
    protected $articles;

    /**
     * @ORM\OneToMany(targetEntity="Tag", mappedBy="parent", cascade={"persist", "remove"})
     */
    protected $sons;

    /**
     * @ORM\ManyToOne(targetEntity="Tag", inversedBy="sons")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", nullable=true)
     */
    protected $parent;

    public function __construct()
    {
        $this->tracks = new ArrayCollection();
        $this->projects = new ArrayCollection();
        $this->articles = new ArrayCollection();
    }

    public function getParent()
    {
        return $this->parent;
    }

    public function setParent($parent)
    {
        $this->parent = $parent;
    }


    public function getTrack()
    {
        return $this->Track;
    }

    public function setTracks($Track)
    {
        $this->Tracks = $Tracks;
    }

    public function addTrack($track)
    {
         if (!$this->tracks->contains($track)) {
            $this->tracks->add($track);
        }
    }

    public function getProjects()
    {
        return $this->projects;
    }

    public function setProjects($projects)
    {
        $this->projects = $projects;
    }

    public function addProject($project)
    {
         if (!$this->projects->contains($project)) {
            $this->projects->add($project);
        }
    }

    public function getArticles()
    {
        return $this->articles;
    }

    public function setArticles($articles)
    {
        $this->articles = $articles;
    }

    public function addArticle($article)
    {
         if (!$this->articles->contains($article)) {
            $this->articles->add($article);
        }
    }

    public function getSons()
    {
        return $this->sons;
    }

    public function setSons($sons)
    {
        $this->sons = $sons;
    }


    public function addSon($son)
    {
        $this->sons[] = $son;
    }

}