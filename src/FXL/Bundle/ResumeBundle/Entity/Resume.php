<?php

namespace FXL\Bundle\MusicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="FXL\Bundle\MusicBundle\Repository\ProjectRepository")
 * @ORM\Table(name="music__article")
 */
class Resume extends BaseObject
{
    /**
     * @ORM\ManyToOne(targetEntity="Identity")
     * @ORM\JoinColumn(name="project_id", referencedColumnName="id", nullable=true)
     */
    protected $identity;

    /**
     * @ORM\ManyToMany(targetEntity="Skill")
     */
    protected $skills;

    /**
     * @ORM\OneToMany(targetEntity="Experience")
     */
    private $experiences;


}