<?php

namespace FXL\Bundle\ResumeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FXL\Bundle\ResumeBundle\Entity\Base\BaseObject;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="FXL\Bundle\ResumeBundle\Repository\TagRepository")
 * @ORM\Table(name="resume__tag")
 */
class Tag extends BaseObject
{
    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $weight;

    public function __construct(){

        $this->skills = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * @param mixed $weight
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;
    }

    /**
     * @return mixed
     */
    public function getWeight()
    {
        return $this->weight;
    }

}
