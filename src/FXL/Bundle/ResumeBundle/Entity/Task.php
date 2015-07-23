<?php

namespace FXL\Bundle\ResumeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FXL\Bundle\ResumeBundle\Entity\Base\BaseObject;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 * @ORM\Table(name="resume__Task")
 */
class Task extends BaseObject
{
    /**
     * @ORM\ManyToOne(targetEntity="FXL\Bundle\ResumeBundle\Entity\Experience", inversedBy="tasks")
     * @ORM\JoinColumn(name="experience_id", referencedColumnName="id", nullable=true)
     */
    protected $experience;

    /**
     * @param mixed $experiences
     */
    public function setExperience($experience)
    {
        $this->experience = $experience;
    }

    /**
     * @return mixed
     */
    public function getExperience()
    {
        return $this->experience;
    }

}
