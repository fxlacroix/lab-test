<?php

namespace FXL\Bundle\MusicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

use Gedmo\Mapping\Annotation as Gedmo; // gedmo annotations

/**
 * @ORM\Entity()
 * @ORM\Table(name="music__act")
 * @ORM\HasLifecycleCallbacks()
 */
class Act extends BaseObject
{
    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $number;

    /**
     * @ORM\ManyToOne(targetEntity="Project")
     * @ORM\JoinColumn(name="project_id", referencedColumnName="id", nullable=true)
     */
    protected $project;

    /**
     * @ORM\OneToMany(targetEntity="Scene", mappedBy="act", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="id", referencedColumnName="act_id", nullable=true)
     */
    private $scenes;

    /**
     * @ORM\OneToMany(targetEntity="Actor", mappedBy="act", cascade={"persist"})
     * @ORM\JoinColumn(name="id", referencedColumnName="act_id", nullable=true)
     */
    private $actors;

    public function __construct(){

        $this->scenes = new \Doctrine\Common\Collections\ArrayCollection;
        $this->actors = new \Doctrine\Common\Collections\ArrayCollection;
    }

    public function getProject()
    {
        return $this->project;
    }

    public function setProject($project)
    {
        $this->project = $project;
    }

    public function getScenes()
    {
        return $this->scenes;
    }

    public function setScenes($scenes)
    {
        $this->scenes = $scenes;
    }


    public function addScene($scenes)
    {
        $this->scene = $scenes;
    }

    public function getNumber()
    {
        return $this->number;
    }

    public function setNumber($number)
    {
        $this->number = $number;
    }

}
