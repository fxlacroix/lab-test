<?php

namespace FXL\Bundle\LiteracyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use FXL\Component\Entity\Base\Base;
use Doctrine\ORM\Mapping\OrderBy;

/**
 * @ORM\Entity()
 * @ORM\Table(name="lab__tag")
 */
class Tag extends Base
{
    /**
     * @ORM\ManyToMany(targetEntity="Node", inversedBy="tags")
     * @ORM\JoinTable(name="lab__tag_node")
     */
    private $nodes;

    /**
     * @ORM\ManyToMany(targetEntity="Sheet", inversedBy="tags")
     * @ORM\JoinTable(name="lab__tag_sheet")
     */
    private $sheets;

     public function getNodes()
    {
        return $this->nodes;
    }

    public function setNodes($nodes)
    {
        $this->nodes = $nodes;
    }

    public function addNode($nodes)
    {
         if (!$this->nodes->contains($nodes)) {
            $this->nodes->add($nodes);
        }
    }

     public function getSheets()
    {
        return $this->sheets;
    }

    public function setSheets($sheets)
    {
        $this->sheets = $sheets;
    }

    public function addSheet($sheets)
    {
         if (!$this->sheets->contains($sheets)) {
            $this->sheets->add($sheets);
        }
    }
}