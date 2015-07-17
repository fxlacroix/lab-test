<?php

namespace FXL\Bundle\LiteracyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FXL\Component\Entity\Base\AttachedDate;

/**
 * Node
 *
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(name="lab__group")
 * @ORM\Entity(repositoryClass="FXL\Bundle\LiteracyBundle\Repository\GroupRepository")
 *
 */
class Group extends AttachedDate
{
    /**
     * @ORM\ManyToOne(targetEntity="Node")
     * @ORM\JoinColumn(name="node_id", referencedColumnName="id")
     */
    private $node;

    /**
     * @ORM\OneToMany(targetEntity="Sheet", mappedBy="group", cascade={"persist", "remove"},  fetch="EAGER")
     * @ORM\JoinColumn(name="id", referencedColumnName="node_id", nullable=true)
     * @ORM\OrderBy({"position" = "ASC"})
     */
    private $sheets;

    /**
     * @ORM\OneToMany(targetEntity="Group", mappedBy="parent", cascade={"persist", "remove"})
     */
    private $sons;

    /**
     * @ORM\ManyToOne(targetEntity="Group", inversedBy="sons", cascade={"persist"})
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", nullable=true)
     */
    private $parent;


    private $structure;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $published = false;

    function __construct()
    {
        $this->sheets = new \Doctrine\Common\Collections\ArrayCollection();

    }
    /**
     * Add sons
     *
     * @param \FXL\Bundle\QCMBundle\Entity\Node $sons
     * @return Node
     */
    public function addSon(\FXL\Bundle\LiteracyBundle\Entity\Group $son)
    {
        $this->sons[] = $son;

        return $this;
    }

    /**
     * Remove sons
     *
     * @param \FXL\Bundle\LiteracyBundle\Entity\Group $sons
     */
    public function removeSon(\FXL\Bundle\LiteracyBundle\Entity\Group $groups)
    {
        $this->sons->removeElement($groups);
    }

    /**
     * Get sons
     *
     * @return \FXL\Bundle\LiteracyBundle\Entity\Group
     */
    public function getSons()
    {
        return $this->sons;
    }


    /**
     * Set parent
     *
     * @param \FXL\Bundle\LiteracyBundle\Entity\Group $parent
     * @return Node
     */
    public function setParent(\FXL\Bundle\LiteracyBundle\Entity\Group $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return \FXL\Bundle\QCMBundle\Entity\Node
     */
    public function getParent()
    {
        return $this->parent;
    }

    public function getStructure()
    {
        return $this->structure;
    }

    public function setStructure($structure)
    {
        $this->structure = $structure;
    }


    public function publish(){

        $this->published = true;
        return $this;
    }

    public function unpublish(){

        $this->published = false;
        return $this;
    }

    public function persistAndFlush(\Doctrine\ORM\EntityManager $em)
    {
        $em->persist($this);
        $em->flush();
        return $this;
    }

    public function toArray(){

        return ;
    }

    public function setPublished($published)
    {
        $this->published = $published;
        return $this;
    }

    public function getPublished()
    {
        return $this->published;
    }

    /**
     * Set node
     *
     * @param \FXL\Bundle\QCMBundle\Entity\Node $node
     * @return Question
     */
    public function setNode(\FXL\Bundle\LiteracyBundle\Entity\Node $node = null)
    {
        $this->node = $node;

        return $this;
    }

    /**
     * Get node
     *
     * @return \FXL\Bundle\QCMBundle\Entity\Node
     */
    public function getNode()
    {
        return $this->node;
    }

    public function getSheets()
    {
        return $this->sheets;
    }

    public function setSheets($sheets)
    {
        $this->sheets = $sheets;
    }


    public function addSheet($sheet)
    {
        $this->sheets[] = $sheet;
    }

    public function getSheetContent()
    {

        $generation = "";

        foreach($this->getSons() as $subGroup){
            $generation .= "\t".$subGroup->getName()."\n\n";
            $generation .= $subGroup->getSheetContent();
        }

        foreach($this->getSheets() as $sheet){

            if($sheet->getPosition()){
                $generation .= "\t".$sheet->getContent()."\n\n\n";
            }
        }
        return $generation;
    }

    /**
     * @ORM\PreUpdate
     * @ORM\PrePersist
     */
    public function preSave()
    {
        // the file property can be empty if the field is not required
       if (null === $this->file) {
            return;
        }
        $this->file->move($this->getUploadRootDir(), $this->file->getClientOriginalName());

        $this->path = $this->getUploadDir().$this->file->getClientOriginalName();
        $this->file = null;

        $this->setUpdatedAt(new \DateTime());

    }

    /**
     * @ORM\PreRemove
     */
    public function preRemove()
    {
        $uri = $this->getPath();

        if ($this->getPath() && file_exists($uri)) {

            unlink($uri);
        }
    }


    public function findUser()
    {
      $o = $this;

      while(! $o->getNode()){

        $o = $o->getParent();
      }

      $user = $o->getNode()->findUser();

      return $user;
    }
}