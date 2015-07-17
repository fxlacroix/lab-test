<?php

namespace FXL\Bundle\MusicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

use Gedmo\Mapping\Annotation as Gedmo; // gedmo annotations

/**
 * @ORM\Entity(repositoryClass="FXL\Bundle\MusicBundle\Repository\DocumentRepository")
 * @ORM\Table(name="music__document")
 */
class Document extends BaseObject
{
    /**
     * @ORM\ManyToOne(targetEntity="Track")
     * @ORM\JoinColumn(name="track_id", referencedColumnName="id", nullable=true)
     */
    protected $track;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $version;

    /**
     * @ORM\ManyToMany(targetEntity="Author", mappedBy="documents")
     */
    protected $authors;

    public function __construct(){

        $this->authors = new \Doctrine\Common\Collections\ArrayCollection;
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
        }
    }

    public function getVersion()
    {
        return $this->version;
    }

    public function setVersion($version)
    {
        $this->version = $version;
    }

    public function getCountry()
    {
        return $this->country;
    }

    public function setCountry($country)
    {
        $this->country = $country;
    }

    public function getProject()
    {
        return $this->project;
    }

    public function setProject($project)
    {
        $this->project = $project;
    }

    public function getTrack()
    {
        return $this->track;
    }

    public function setTrack($track)
    {
        $this->track = $track;
    }

}