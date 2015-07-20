<?php

namespace FXL\Bundle\MusicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="FXL\Bundle\MusicBundle\Repository\ProjectRepository")
 * @ORM\Table(name="music__article")
 */
class Identity
{
    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $firstName;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $lastName;

}