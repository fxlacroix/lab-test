<?php

namespace FXL\Bundle\MusicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

use Gedmo\Mapping\Annotation as Gedmo; // gedmo annotations

/**
 * @ORM\Entity()
 * @ORM\Table(name="music__scene")
 * @ORM\HasLifecycleCallbacks()
 */
class Scene extends BaseObject
{
    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $number;

    /**
     * @ORM\ManyToOne(targetEntity="Act")
     * @ORM\JoinColumn(name="act_id", referencedColumnName="id", nullable=true)
     */
    protected $act;

    /**
     * @ORM\ManyToMany(targetEntity="Actor", mappedBy="scenes", cascade={"persist"})
     */
    private $actors;

    /**
     * @ORM\ManyToOne(targetEntity="Project")
     * @ORM\JoinColumn(name="project_id", referencedColumnName="id", nullable=true)
     */
    protected $project;


    public function getAct()
    {
        return $this->act;
    }

    public function setAct($act)
    {
        $this->act = $act;
    }

    public function getActors()
    {
        return $this->project;
    }

    public function setActors($actors)
    {
        $this->actors = $actors;
    }

    public function addActor($actor)
    {
        $this->actors[] = $actor;
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
