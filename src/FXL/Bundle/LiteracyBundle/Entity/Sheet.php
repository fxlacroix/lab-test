<?php

namespace FXL\Bundle\LiteracyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use FXL\Component\Entity\Base\Article;

/**
 * Sheet
 *
 * @ORM\Table(name="lab__sheet")
 * @ORM\Entity(repositoryClass="FXL\Bundle\LiteracyBundle\Repository\SheetRepository")
 */
class Sheet extends Article
{
    /**
     * @ORM\ManyToOne(targetEntity="Group")
     * @ORM\JoinColumn(name="group_id", referencedColumnName="id")
     */
    private $group;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $note;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $position;

    public function getPosition()
    {
        return $this->position;
    }

    public function setPosition($position)
    {
        $this->position = $position;
    }

    public function getGroup()
    {
        return $this->group;
    }

    public function setGroup($group)
    {
        $this->group = $group;
    }


    public function getNote()
    {
        return $this->note;
    }

    public function setNote($note)
    {
        $this->note = $note;
    }


}