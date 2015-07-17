<?php

namespace FXL\Bundle\MusicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="FXL\Bundle\MusicBundle\Repository\ProjectRepository")
 * @ORM\Table(name="music__article")
 */
class Article extends BaseObject
{
    /**
     * @ORM\ManyToOne(targetEntity="Project")
     * @ORM\JoinColumn(name="project_id", referencedColumnName="id", nullable=true)
     */
    protected $project;

    /**
     * @ORM\ManyToMany(targetEntity="Author", mappedBy="articles")
     */
    protected $authors;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $content;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $number;

    /**
     * @ORM\OneToMany(targetEntity="Asset", mappedBy="article", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="id", referencedColumnName="article_id", nullable=true)
     */
    private $assets;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $published;

    /**
     * @ORM\ManyToMany(targetEntity="Tag", mappedBy="projects", cascade={"persist"})
     */
    private $tags;


    public function __construct() {

        $this->authors = new \Doctrine\Common\Collections\ArrayCollection;
        $this->assets = new \Doctrine\Common\Collections\ArrayCollection;
    }

    public function getNumber()
    {
        return $this->number;
    }

    public function setNumber($number)
    {
        $this->number = $number;
    }

    public function getProject()
    {
        return $this->project;
    }

    public function setProject($project)
    {
        $this->project = $project;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function setContent($content)
    {
        $this->content = $content;
    }

    public function getAuthors()
    {
        return $this->authors;
    }

    public function setAuthors($authors)
    {
        $this->authors = $authors;
    }

    public function addAuthors($author)
    {
        if (!$this->authors->contains($author)) {
            $this->authors[] = $author;
        }
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
        if (!$this->assets->contains($asset)) {
            $this->assets[] = $asset;
        }
    }

    public function getPublished()
    {
        return $this->published;
    }

    public function setPublished($published)
    {
        $this->published = $published;
    }

    public function getTags()
    {
        return $this->tags;
    }

    public function setTags($tags)
    {
        $this->tags = $tags;
    }

    public function addTag($tag)
    {
        $this->tags[] = $tag;
    }


}