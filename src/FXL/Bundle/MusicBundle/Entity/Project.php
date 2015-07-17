<?php

namespace FXL\Bundle\MusicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="FXL\Bundle\MusicBundle\Repository\ProjectRepository")
 * @ORM\Table(name="music__project")
 */
class Project extends BaseObject
{
    /**
     * @ORM\OneToMany(targetEntity="Track", mappedBy="project", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="id", referencedColumnName="project_id", nullable=true)
     */
    private $tracks;

    /**
     * @ORM\OneToMany(targetEntity="Article", mappedBy="project", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="id", referencedColumnName="project_id", nullable=true)
     */
    private $articles;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $logo;

    /**
     * @Assert\File(maxSize="1000000",
     *      mimeTypes = {"image/jpeg", "image/gif", "image/png"},
     *      mimeTypesMessage = "Please upload an image")
     */
    protected $image;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $content;

    /**
     * @ORM\OneToMany(targetEntity="Asset", mappedBy="project", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="id", referencedColumnName="project_id", nullable=true)
     */
    private $assets;

    /**
     * @ORM\ManyToMany(targetEntity="Author", mappedBy="projects", cascade={"persist"})
     */
    protected $authors;

    /**
     * @ORM\ManyToOne(targetEntity="\FXL\Bundle\userBundle\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=true)
     */
    private $user;

    /**
     * @ORM\ManyToMany(targetEntity="Tag", mappedBy="projects", cascade={"persist"})
     */
    private $tags;

    /**
     * @ORM\OneToMany(targetEntity="Act", mappedBy="project", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="id", referencedColumnName="project_id", nullable=true)
     */
    private $acts;

    public function __construct()
    {
        $this->tracks = new ArrayCollection();
        $this->assets = new ArrayCollection();
        $this->authors = new ArrayCollection();
        $this->tags = new ArrayCollection();
        $this->acts = new ArrayCollection();
    }

    public function getActs()
    {
        return $this->acts;
    }

    public function setActs($acts)
    {
        $this->acts = $acts;
    }

    public function addAct($act)
    {
        $this->acts[] = $act;
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
        if (!$this->authors->contains($author)) {
            $this->authors[] = $author;
        }
    }

    public function removeAuthor($author){

        if ($this->authors->contains($author)) {
            $this->authors->remove($author);
        }
    }

    public function getTracks()
    {
        return $this->tracks;
    }

    public function addTrack($track)
    {
        if (!$this->tracks->contains($track)) {
            $this->tracks[] = $track;
        }
    }

    public function setTracks($tracks)
    {
        $this->tracks = $tracks;
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

    public function getImage()
    {
        return $this->image;
    }

    public function setImage($image)
    {
        $this->image = $image;
    }

    public function getLogo()
    {
        return $this->logo;
    }

    public function setLogo($logo)
    {
        $this->logo = $logo;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @ORM\PreUpdate
     * @ORM\PrePersist
     */
    public function preSave()
    {
        // the file property can be empty if the field is not required
        if (null === $this->image) {
            return;
        }
        $this->image->move(__DIR__ . '/../../../../../web/uploads/Project/images/' . $this->getName(), $this->image->getClientOriginalName());
        $this->logo = $this->image->getClientOriginalName();
        $this->image = null;
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
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
        }
    }

    public function removeTag($tag){

        if ($this->tags->contains($tag)) {
            $this->tags->remove($tag);
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



}