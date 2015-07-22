<?php

namespace FXL\Bundle\ResumeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use FXL\Bundle\ResumeBundle\Entity\Base\BaseObject;

/**
 * @ORM\Entity(repositoryClass="FXL\Bundle\ResumeBundle\Repository\ResumeRepository")
 * @ORM\Table(name="resume__resume")
 */
class Resume extends BaseObject
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
    protected $yearsOfExperience;

    /**
     * @ORM\OneToOne(targetEntity="Identity", cascade={"persist"})
     * @ORM\JoinColumn(name="project_id", referencedColumnName="id", nullable=true)
     */
    protected $identity;

    /**
     * @ORM\OneToMany(targetEntity="Skill", mappedBy="resume", cascade={"persist"})
     */
    protected $skills;

    /**
     *
     * @ORM\OneToMany(targetEntity="Experience", mappedBy="resume", cascade={"persist"})
     */
    private $experiences;

    /**
     * @ORM\OneToMany(targetEntity="Leisure", mappedBy="resume", cascade={"persist"})
     */
    private $leisures;

    /**
     * @ORM\OneToMany(targetEntity="Study", mappedBy="resume", cascade={"persist"})
     */
    private $studies;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $language;

    /**
     * @ORM\OneToMany(targetEntity="Trump", mappedBy="resume", cascade={"persist"})
     */
    private $trumps;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->skills = new \Doctrine\Common\Collections\ArrayCollection();
        $this->experiences = new \Doctrine\Common\Collections\ArrayCollection();
        $this->leisures = new \Doctrine\Common\Collections\ArrayCollection();
        $this->studies = new \Doctrine\Common\Collections\ArrayCollection();
        $this->trumps= new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set identity
     *
     * @param \FXL\Bundle\ResumeBundle\Entity\Identity $identity
     * @return Resume
     */
    public function setIdentity(\FXL\Bundle\ResumeBundle\Entity\Identity $identity = null)
    {
        $this->identity = $identity;

        return $this;
    }

    /**
     * Get identity
     *
     * @return \FXL\Bundle\ResumeBundle\Entity\Identity
     */
    public function getIdentity()
    {
        return $this->identity;
    }

    /**
     * @param mixed $yearsOfExperience
     */
    public function setYearsOfExperience($yearsOfExperience)
    {
        $this->yearsOfExperience = $yearsOfExperience;
    }

    /**
     * @return mixed
     */
    public function getYearsOfExperience()
    {
        return $this->yearsOfExperience;
    }

    /**
     * Add skills
     *
     * @param \FXL\Bundle\ResumeBundle\Entity\Skill $skills
     * @return Resume
     */
    public function addSkill(\FXL\Bundle\ResumeBundle\Entity\Skill $skills)
    {
        $this->skills[] = $skills;

        return $this;
    }

    /**
     * Remove skills
     *
     * @param \FXL\Bundle\ResumeBundle\Entity\Skill $skills
     */
    public function removeSkill(\FXL\Bundle\ResumeBundle\Entity\Skill $skills)
    {
        $this->skills->removeElement($skills);
    }

    /**
     * Get skills
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSkills()
    {
        return $this->skills;
    }

    /**
     * Add experiences
     *
     * @param \FXL\Bundle\ResumeBundle\Entity\Experience $experiences
     * @return Resume
     */
    public function addExperience(\FXL\Bundle\ResumeBundle\Entity\Experience $experiences)
    {
        $this->experiences[] = $experiences;

        return $this;
    }

    /**
     * Remove experiences
     *
     * @param \FXL\Bundle\ResumeBundle\Entity\Experience $experiences
     */
    public function removeExperience(\FXL\Bundle\ResumeBundle\Entity\Experience $experiences)
    {
        $this->experiences->removeElement($experiences);
    }

    /**
     * Get experiences
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getExperiences()
    {
        return $this->experiences;
    }

    /**
     * Add leisures
     *
     * @param \FXL\Bundle\ResumeBundle\Entity\Leisure $leisures
     * @return Resume
     */
    public function addLeisure(\FXL\Bundle\ResumeBundle\Entity\Leisure $leisures)
    {
        $this->leisures[] = $leisures;

        return $this;
    }

    /**
     * Remove experiences
     *
     * @param \FXL\Bundle\ResumeBundle\Entity\Experience $experiences
     */
    public function removeLeisure(\FXL\Bundle\ResumeBundle\Entity\Leisure $leisures)
    {
        $this->leisures->removeElement($leisures);
    }

    /**
     * Get experiences
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLeisures()
    {
        return $this->leisures;
    }

    /**
     * Add Studies
     *
     * @param \FXL\Bundle\ResumeBundle\Entity\Study $studies
     * @return Resume
     */
    public function addStudy(\FXL\Bundle\ResumeBundle\Entity\Study $studies)
    {
        $this->studies[] = $studies;

        return $this;
    }

    /**
     * Remove studies
     *
     * @param \FXL\Bundle\ResumeBundle\Entity\Study $studies
     */
    public function removeStudy(\FXL\Bundle\ResumeBundle\Entity\Study $studies)
    {
        $this->studies->removeElement($studies);
    }

    /**
     * Get studies
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getStudies()
    {
        return $this->studies;
    }

    /**
     * @param mixed $trumps
     */
    public function setTrumps($trumps)
    {
        $this->trumps = $trumps;
    }

    /**
     * @return mixed
     */
    public function getTrumps()
    {
        return $this->trumps;
    }

    /**
     * Add $trumps
     *
     * @param \FXL\Bundle\ResumeBundle\Entity\Trump $trumps
     * @return Resume
     */
    public function addTrump(\FXL\Bundle\ResumeBundle\Entity\Trump $trumps)
    {
        $this->trumps[] = $trumps;

        return $this;
    }

    /**
     * Remove $trumps
     *
     * @param \FXL\Bundle\ResumeBundle\Entity\Trump $trumps
     */
    public function removeTrump(\FXL\Bundle\ResumeBundle\Entity\Trump $trumps)
    {
        $this->studies->removeElement($trumps);
    }

    /**
     * @param mixed $language
     */
    public function setLanguage($language)
    {
        $this->language = $language;
    }

    /**
     * @return mixed
     */
    public function getLanguage()
    {
        return $this->language;
    }

}
