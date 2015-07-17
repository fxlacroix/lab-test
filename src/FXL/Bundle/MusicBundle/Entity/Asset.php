<?php

namespace FXL\Bundle\MusicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

use Gedmo\Mapping\Annotation as Gedmo; // gedmo annotations

/**
 * @ORM\Entity(repositoryClass="FXL\Bundle\MusicBundle\Repository\AssetRepository")
 * @ORM\Table(name="music__asset")
 * @ORM\HasLifecycleCallbacks()
 */
class Asset
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="Project", inversedBy="assets")
     * @ORM\JoinColumn(name="project_id", referencedColumnName="id", nullable=true)
     */
    protected $project;

    /**
     * @ORM\ManyToOne(targetEntity="Track", inversedBy="assets")
     * @ORM\JoinColumn(name="track_id", referencedColumnName="id", nullable=true)
     */
    protected $track;

    /**
     * @ORM\ManyToOne(targetEntity="Article", inversedBy="assets")
     * @ORM\JoinColumn(name="article_id", referencedColumnName="id", nullable=true)
     */
    protected $article;

    /**
     * @ORM\ManyToOne(targetEntity="Author", inversedBy="assets")
     * @ORM\JoinColumn(name="author_id", referencedColumnName="id", nullable=true)
     */
    protected $author;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $description;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $path;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $url;

    /**
     * @Assert\File(maxSize="20000000")
     */
    protected $file;


    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getPath()
    {
        return $this->path;
    }

    public function setPath($path)
    {
        $this->path = $path;
    }

    public function getFile()
    {
        return $this->file;
    }

    public function setFile($file)
    {
        $this->file = $file;
    }

    public function getAbsolutePath()
    {
        return null === $this->path ? null : $this->getUploadRootDir().'/'.$this->path;
    }

    public function getWebPath()
    {
        return null === $this->path ? null : $this->getUploadDir().'/'.$this->path;
    }

    protected function getUploadRootDir()
    {
        // the absolute directory path where uploaded documents should be saved
        return __DIR__.'/../../../../../web/'.$this->getUploadDir();
    }

    protected function getUploadDir()
    {
        if($this->getAuthor()){

            $path = "author/".$this->getAuthor()->getSlug()."/";

        }elseif($this->getArticle()){

            $path = "project/".$this->getArticle()->getProject()->getSlug()."/";
            $path .= "article/".$this->getArticle()->getSlug()."/";

        }elseif($this->getTrack()){

            $path = "project/".$this->getTrack()->getProject()->getSlug()."/";
            $path .= "track/".$this->getTrack()->getSlug()."/";

        }elseif($this->getProject()){

            $path = "project/".$this->getProject()->getSlug()."/";

        }

        // get rid of the __DIR__ so it doesn't screw when displaying uploaded doc/image in the view.
        return '/uploads/Asset/'.$path;
    }

    /**
     * @ORM\PreUpdate
     * @ORM\PrePersist
     */
    public function preSave()
    {
        // the file property can be empty if the field is not required
       if (null === $this->file) {

            if($this->url && !$this->path) {

                $urlPath = basename(parse_url($this->url, PHP_URL_PATH));

                if(!file_exists($this->getUploadRootDir())) {
                    mkdir($this->getUploadRootDir(), 0777);
                }

                $uri = $this->getUploadRootDir().$urlPath;

                $fileContent = file_get_contents($this->url);

                file_put_contents($uri, $fileContent);

                $this->path = $this->getUploadDir().$urlPath;

                return;

            }

            return;
        }

        $this->file->move($this->getUploadRootDir(), $this->file->getClientOriginalName());

        $this->path = $this->getUploadDir().$this->file->getClientOriginalName();
        $this->file = null;

    }

    /**
     * @ORM\PreRemove
     */
    public function preRemove()
    {
        $uri = $this->getUploadRootDir().$this->getPath();

        if ($this->getPath() && file_exists($uri)) {

            unlink($uri);
        }
    }

    public function getProject()
    {
        return $this->project;
    }

    public function setProject($project)
    {
        $this->project = $project;
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function setUrl($url)
    {
        $this->url = $url;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getTrack()
    {
        return $this->track;
    }

    public function setTrack($track)
    {
        $this->track = $track;
    }

    public function getArticle()
    {
        return $this->article;
    }

    public function setArticle($article)
    {
        $this->article = $article;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setType($type)
    {
        $this->type = $type;
    }

    static public function getTypeAssets() {

        return array(
            'background' => 'background',
            'logo'       => 'logo',
            'picture'    => 'picture',
            'audio'      => 'audio',
            'text'       => 'text'
        );
    }

    public function getAuthor()
    {
        return $this->author;
    }

    public function setAuthor($author)
    {
        $this->author = $author;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }




}
