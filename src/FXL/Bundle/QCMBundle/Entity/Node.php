<?php

namespace FXL\Bundle\QCMBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FXL\Component\Entity\Base;

/**
 * @ORM\Entity(repositoryClass="FXL\Bundle\QCMBundle\Repository\NodeRepository")
 * @ORM\Table(name="qcm__node")
 */
class Node extends Base
{
    /**
     * @ORM\ManyToOne(targetEntity="QCM")
     * @ORM\JoinColumn(name="qcm_id", referencedColumnName="id")
     */
    private $qcm;

    /**
     * @ORM\ManyToOne(targetEntity="Node", inversedBy="sons")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", nullable=true)
     */

    private $parent;

    /**
     * @ORM\OneToMany(targetEntity="Node", mappedBy="parent", cascade={"persist", "remove"})
     */
    private $sons;

    /**
     * @ORM\OneToMany(targetEntity="Question", mappedBy="node", cascade={"persist", "remove"}, fetch="EAGER")
     * @ORM\JoinColumn(name="id", referencedColumnName="node_id", nullable=true)
     */
    private $questions;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $time;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->questions = new \Doctrine\Common\Collections\ArrayCollection();
        $this->sons      = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set time
     *
     * @param integer $time
     * @return Node
     */
    public function setTime($time)
    {
        $this->time = $time;

        return $this;
    }

    /**
     * Get time
     *
     * @return integer
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * Set qcm
     *
     * @param \FXL\Bundle\QCMBundle\Entity\QCM $qcm
     * @return Node
     */
    public function setQcm(\FXL\Bundle\QCMBundle\Entity\QCM $qcm = null)
    {
        $this->qcm = $qcm;

        return $this;
    }

    /**
     * Get qcm
     *
     * @return \FXL\Bundle\QCMBundle\Entity\QCM
     */
    public function getQcm()
    {
        return $this->qcm;
    }

    /**
     * Add questions
     *
     * @param \FXL\Bundle\QCMBundle\Entity\Question $questions
     * @return Node
     */
    public function addQuestion(\FXL\Bundle\QCMBundle\Entity\Question $questions)
    {
        $this->questions[] = $questions;

        return $this;
    }

    /**
     * Remove questions
     *
     * @param \FXL\Bundle\QCMBundle\Entity\Question $questions
     */
    public function removeQuestion(\FXL\Bundle\QCMBundle\Entity\Question $questions)
    {
        $this->questions->removeElement($questions);
    }

    /**
     * Get questions
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getQuestions()
    {
        return $this->questions;
    }

    /**
     * Add sons
     *
     * @param \FXL\Bundle\QCMBundle\Entity\Node $sons
     * @return Node
     */
    public function addSon(\FXL\Bundle\QCMBundle\Entity\Node $sons)
    {
        $this->sons[] = $sons;

        return $this;
    }

    /**
     * Remove sons
     *
     * @param \FXL\Bundle\QCMBundle\Entity\Node $sons
     */
    public function removeSon(\FXL\Bundle\QCMBundle\Entity\Node $sons)
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
    public function setParent(\FXL\Bundle\QCMBundle\Entity\Node $parent = null)
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

    public function getRecursiveQcm(){

        $parent = $this->parent;

        if(null === $parent){
            if($this->qcm){
                return $this->qcm;
            }

        }else{

            return $this->getParent()->getRecursiveQcm();
        }

        return null;
    }

    public function getNodesWithQuestion()
    {
        $subjects = array();

        if($this->getQuestions()->count()){

            $subjects[] = $this->getName();
        }

        foreach($this->getSons() as $node){

            $subjects = array_merge($subjects, $node->getNodesWithQuestion());
        }

        return array_unique($subjects);
    }

    public function countQuestions() {

        $total = 0;

        foreach($this->getSons() as $son){

            $total += $son->countQuestions();
        }

        $total += $this->getQuestions()->count();

        return $total;
    }

    /**
     *
     */
    public function findQuestions() {

        $questions = array();
        foreach($this->getSons() as $node){

            $questions = array_merge($questions, $node->findQuestions());
        }

        $questions = array_merge($this->getQuestions()->toArray(), $questions);
        
        return $questions;
    }
}