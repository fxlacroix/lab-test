<?php

namespace FXL\Bundle\ResumeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FXL\Bundle\ResumeBundle\Entity\Base\BaseObject;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 * @ORM\Table(name="resume__trump")
 */
class Trump extends BaseObject
{
    /**
     * @ORM\ManyToOne(targetEntity="Resume", inversedBy="trumps")
     * @ORM\JoinColumn(name="resume_id", referencedColumnName="id", nullable=true)
     */
    protected $resume;

    /**
     * @param mixed $resume
     */
    public function setResume($resume)
    {
        $this->resume = $resume;
    }

    /**
     * @return mixed
     */
    public function getResume()
    {
        return $this->resume;
    }


}
