<?php

namespace FXL\Bundle\ResumeBundle\Entity\Base;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use FXL\Bundle\ResumeBundle\Entity\Base\DateObject;
use Gedmo\Mapping\Annotation as Gedmo; // gedmo annotations

/**
 * @ORM\MappedSuperclass
 * @ORM\HasLifecycleCallbacks()
 */
class BaseObject extends DateObject
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    protected $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $type;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $description;

    /**
     * @Assert\File(maxSize="20000000")
     */
    protected $file;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $path;

    /**
     * @Gedmo\Slug(fields={"name"}, updatable=false, separator="-")
     * @ORM\Column(length=32, unique=true, nullable=true)
     */
    protected $slug;

    public function __toString()
    {
        return $this->name;
    }

    public function getSlug()
    {
        return $this->slug;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name){
        $this->name = $name;
    }
    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getAbsolutePath()
    {
        return null === $this->path ? null : $this->getUploadRootDir().'/'.$this->path;
    }

    public function getWebPath()
    {
        return null === $this->path ? null : $this->getUploadDir().'/'.$this->path;
    }

    public function getUploadRootDir()
    {
        // the absolute directory path where uploaded documents should be saved
        return __DIR__.'/../../../../../web/'.$this->getUploadDir();
    }

    protected function getUploadDir()
    {
        $class = str_replace(__NAMESPACE__."\\", "", get_called_class());

        $project = "";
        if($this instanceof Document) {
            $project = $class."/".$this->getTrack()->getProject()->getSlug()."/".$this->getTrack()->getSlug()."/".$this->getVersion()."/";
        }

        // get rid of the __DIR__ so it doesn't screw when displaying uploaded doc/image in the view.
        return "uploads/".$project;
    }

    public function getFile()
    {
        return $this->file;
    }

    public function setFile($file)
    {
        $this->file = $file;
    }

    public function getPath()
    {
        return $this->path;
    }

    public function setPath($path)
    {
        $this->path = $path;
    }

    /**
     * @ORM\PreUpdate
     * @ORM\PrePersist
     */
    public function preSave()
    {
        // the file property can be empty if the field is not required
       if (null === $this->file) {
            return;
        }

        $class = str_replace(__NAMESPACE__."\\", "", get_called_class());

        $this->type = $class;

        $this->file->move($this->getUploadRootDir(), $this->file->getClientOriginalName());

        $project = "";
        if($this instanceof Document) {
            $project = $class."/".$this->getTrack()->getProject()->getSlug()."/".$this->getTrack()->getSlug()."/".$this->getVersion()."/";
        }

        $this->path = "/uploads/".$project.$this->file->getClientOriginalName();
        $this->file = null;

        $this->setUpdatedAt(new \DateTime());

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

    public function getType() {
        return $this->type;
    }

    public function setType($type) {
        $this->type = $type;
    }
}
