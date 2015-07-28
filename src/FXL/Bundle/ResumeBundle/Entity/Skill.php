<?php

namespace FXL\Bundle\ResumeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="FXL\Bundle\ResumeBundle\Repository\SkillRepository")
 * @ORM\Table(name="resume__skill")
 */
class Skill
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $name;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $level;

    /**
     * @ORM\ManyToMany(targetEntity="Tag", cascade={"persist"})
     */
    protected $tags;

    /**
     * @ORM\ManyToOne(targetEntity="Resume", inversedBy="skills", cascade={"persist"})
     * @ORM\JoinColumn(name="resume_id", referencedColumnName="id", nullable=true)
     */
    protected $resume;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->tags = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Skill
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set level
     *
     * @param string $level
     * @return Skill
     */
    public function setLevel($level)
    {
        $this->level = $level;

        return $this;
    }

    /**
     * Get level
     *
     * @return string 
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * Add tags
     *
     * @param \FXL\Bundle\ResumeBundle\Entity\Tag $tags
     * @return Skill
     */
    public function addTag(\FXL\Bundle\ResumeBundle\Entity\Tag $tags)
    {
        $this->tags[] = $tags;

        return $this;
    }

    /**
     * Remove tags
     *
     * @param \FXL\Bundle\ResumeBundle\Entity\Tag $tags
     */
    public function removeTag(\FXL\Bundle\ResumeBundle\Entity\Tag $tags)
    {
        $this->tags->removeElement($tags);
    }

    /**
     * Get tags
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * Set resume
     *
     * @param \FXL\Bundle\ResumeBundle\Entity\Resume $resume
     * @return Skill
     */
    public function setResume(\FXL\Bundle\ResumeBundle\Entity\Resume $resume = null)
    {
        $this->resume = $resume;

        return $this;
    }

    /**
     * Get resume
     *
     * @return \FXL\Bundle\ResumeBundle\Entity\Resume 
     */
    public function getResume()
    {
        return $this->resume;
    }
}
