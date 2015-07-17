<?php

namespace FXL\Bundle\MusicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

use Gedmo\Mapping\Annotation as Gedmo; // gedmo annotations

/**
 * @ORM\Entity()
 * @ORM\Table(name="music__actor")
 * @ORM\HasLifecycleCallbacks()
 */
class Actor extends BaseObject
{
    /**
     * @ORM\ManyToOne(targetEntity="Project")
     * @ORM\JoinColumn(name="project_id", referencedColumnName="id", nullable=true)
     */
    protected $project;

    /**
     * @ORM\ManyToMany(targetEntity="Scene", inversedBy="actors")
     * @ORM\JoinTable(name="music__actor_scene")
     */
    protected $scenes;

    public function __construct(){

        $this->scenes = new \Doctrine\Common\Collections\ArrayCollection;
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

    public function addScene($scene)
    {
        $this->scenes[] = $scene;
    }
}
