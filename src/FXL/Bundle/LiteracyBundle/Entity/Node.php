<?php

namespace FXL\Bundle\LiteracyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FXL\Component\Entity\Base\Base;
use FXL\Component\Entity\Base\Folder;

/**
 * Node
 *
 * @ORM\Table(name="lab__node")
 * @ORM\Entity(repositoryClass="FXL\Bundle\LiteracyBundle\Repository\NodeRepository")
 *
 */
class Node extends Base
{ /**
     * @ORM\OneToMany(targetEntity="Node", mappedBy="parent", cascade={"persist", "remove"})
     * @ORM\OrderBy({"name" = "ASC"})
     */
    private $sons;

    /**
     * @ORM\ManyToOne(targetEntity="Node", inversedBy="sons")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", nullable=true)
     */
    private $parent;

    /**
     * @ORM\ManyToOne(targetEntity="\FXL\Bundle\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=true)
     */
    protected $user;


    /**
     * @ORM\OneToMany(targetEntity="Group", mappedBy="node", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="id", referencedColumnName="group_id", nullable=true)
     */
    private $groups;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->sons = new \Doctrine\Common\Collections\ArrayCollection();
        $this->groups = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add sons
     *
     * @param \FXL\Bundle\QCMBundle\Entity\Node $sons
     * @return Node
     */
    public function addSon(\FXL\Bundle\LiteracyBundle\Entity\Node $sons)
    {
        $this->sons[] = $sons;

        return $this;
    }

    /**
     * Remove sons
     *
     * @param \FXL\Bundle\QCMBundle\Entity\Node $sons
     */
    public function removeSon(\FXL\Bundle\LiteracyBundle\Entity\Node $sons)
    {
        $this->sons->removeElement($sons);
    }

    /**
     * Get sons
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSons()
    {
        return $this->sons;
    }

    /**
     * Set parent
     *
     * @param \FXL\Bundle\QCMBundle\Entity\Node $parent
     * @return Node
     */
    public function setParent(\FXL\Bundle\LiteracyBundle\Entity\Node $parent = null)
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


    public function getUser()
    {
        return $this->user;
    }

    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * Add groups
     *
     * @param \FXL\Bundle\LiteracyBundle\Entity\Group $groups
     * @return Node
     */
    public function addGroup(\FXL\Bundle\LiteracyBundle\Entity\Group $groups)
    {
        $this->groups[] = $groups;

        return $this;
    }

    /**
     * Remove groups
     *
     * @param \FXL\Bundle\LiteracyBundle\Entity\Group $groups
     */
    public function removeGroup(\FXL\Bundle\LiteracyBundle\Entity\Group $groups)
    {
        $this->groups->removeElement($groups);
    }

    /**
     * Get groups
     *
     * @return \FXL\Bundle\LiteracyBundle\Entity\Group
     */
    public function getGroups()
    {
        return $this->groups;
    }


    public function findUser()
    {
      $o = $this;

      while(! $o->getUser()){

        $o = $o->getParent();
      }

      return  $o->getUser();
    }
}