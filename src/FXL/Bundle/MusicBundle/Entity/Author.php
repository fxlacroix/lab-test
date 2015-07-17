<?php

namespace FXL\Bundle\MusicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="FXL\Bundle\MusicBundle\Repository\AuthorRepository")
 * @ORM\Table(name="music__author")
 */
class Author extends BaseObject
{
    /**
     * @var string
     */
    public $lookup;

    public $authors_lookup;

    /**
     * @ORM\Column(type="integer", unique=true, nullable=true)
     */
    private $wikipediaId;

    /**
     * @ORM\ManyToMany(targetEntity="Document", inversedBy="authors")
     * @ORM\JoinTable(name="music__author_document")
     */
    private $documents;

    /**
     * @ORM\ManyToMany(targetEntity="Project", inversedBy="authors")
     * @ORM\JoinTable(name="music__author_project")
     */
    protected $projects;

    /**
     * @ORM\ManyToMany(targetEntity="Track", inversedBy="authors")
     * @ORM\JoinTable(name="music__author_track")
     */
    private $tracks;

    /**
     * @ORM\ManyToMany(targetEntity="Article", inversedBy="authors")
     * @ORM\JoinTable(name="music__author_article")
     */
    private $articles;

    /**
     * @ORM\OneToMany(targetEntity="Asset", mappedBy="author", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="id", referencedColumnName="author_id", nullable=true)
     */
    private $assets;

    /**
     * @ORM\OneToOne(targetEntity="\FXL\Bundle\CommonBundle\Entity\User", mappedBy="author")
     */
    private $user;

    public function __construct(){

        $this->documents = new ArrayCollection();
        $this->projects = new ArrayCollection();
        $this->tracks = new ArrayCollection();
        $this->articles = new ArrayCollection();
        $this->assets = new ArrayCollection();
    }

    public function getDocuments()
    {
        return $this->documents;
    }

    public function setDocuments($documents)
    {
        $this->documents = $documents;
    }

    public function addDocument($document)
    {
        $this->documents[] = $document;
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
        $this->projects[] = $project;
    }

    public function getTracks()
    {
        return $this->tracks;
    }

    public function setTracks($tracks)
    {
        $this->tracks = $tracks;
    }

    public function addTrack($track)
    {
        if (!$this->tracks->contains($track)) {
            $this->tracks[] = $track;
        }
    }


    public function removeTrack($track)
    {
        if($this->tracks->contains($track)){
            $this->tracks->remove($track);
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
            $this->articles[] = $article;
        }
    }

    public function getUser()
    {
        return $this->user;
    }

    public function setUser($user)
    {
        $this->user = $user;
    }

    public function getWikipediaId()
    {
        return $this->wikipediaId;
    }

    public function setWikipediaId($wikipediaId)
    {
        $this->wikipediaId = $wikipediaId;
    }

    public function getAssets()
    {
        return $this->assets;
    }

    public function setAssets($assets)
    {
        $this->assets = $assets;
    }


    public function addAsset($asset)
    {
        $this->assets[] = $asset;
    }

}