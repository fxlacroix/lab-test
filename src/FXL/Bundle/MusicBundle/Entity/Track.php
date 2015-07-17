<?php

namespace FXL\Bundle\MusicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 * @ORM\Table(name="music__track")
 */
class Track extends BaseObject
{
    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    public $number;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $content;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $chords;

    /**
     * @ORM\ManyToMany(targetEntity="Tag", mappedBy="tracks", cascade={"persist"})
     */
    private $tags;

    /**
     * @ORM\ManyToOne(targetEntity="Project")
     * @ORM\JoinColumn(name="project_id", referencedColumnName="id", nullable=true)
     */
    protected $project;

    /**
     * @ORM\OneToMany(targetEntity="Document", mappedBy="track", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="id", referencedColumnName="track_id", nullable=true)
     */
    private $documents;

    /**
     * @ORM\ManyToMany(targetEntity="Author", mappedBy="tracks", cascade={"persist"})
     */
    private $authors;

    /**
     * @ORM\OneToMany(targetEntity="Asset", mappedBy="track", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="id", referencedColumnName="track_id", nullable=true)
     */
    private $assets;

    public function __construct(){

        $this->documents = new ArrayCollection();
        $this->authors = new ArrayCollection();
        $this->assets = new ArrayCollection();
    }

    public function getAssets()
    {
        return $this->assets;
    }

    public function setAssets($assets)
    {
        $this->assets = $assets;
    }

    public function addAssets($asset)
    {
        if(!$this->assets->contains($asset)){
            $this->assets[] = $asset;
        }
    }

    public function removeAsset($asset)
    {
        if($this->assets->contains($asset)){
            $this->assets->remove($asset);
        }
    }

    public function getAuthors()
    {
        return $this->authors;
    }

    public function setAuthors($authors)
    {
        $this->authors = $authors;
    }

    public function addAuthor($author)
    {
        if(!$this->authors->contains($author)){
            $this->authors[] = $author;
            $author->addTrack($this);
        }
    }

    public function removeAuthor($author)
    {
        if($this->authors->contains($author)){
            $this->authors->remove($author);
        }
    }

    public function getNumber()
    {
        return $this->number;
    }

    public function setNumber($number)
    {
        $this->number = $number;
    }

    public function getDocuments()
    {
        return $this->documents;
    }

    public function addDocument($document)
    {
        if(!$this->documents->contains($document)){

            $this->documents[] = $document;
        }
    }

    public function setDocuments($documents)
    {
        $this->documents = $documents;
    }

    public function getProject()
    {
        return $this->project;
    }

    public function setProject($project)
    {
        $this->project = $project;
    }

    public function getTags()
    {
        return $this->tags;
    }

    public function setTags($tags)
    {
        foreach ($tags as $tag) {
            $tag->addTrack($this);
        }
        $this->tags = $tags;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function setContent($content)
    {
        $this->content = $content;
    }

    public function getLookFor()
    {
        return $this->lookFor;
    }

    public function setLookFor($lookFor)
    {
        $this->lookFor = $lookFor;
    }

    public function getChords()
    {
        return $this->chords;
    }

    public function setChords($chords)
    {
        $this->chords = $chords;
    }

}