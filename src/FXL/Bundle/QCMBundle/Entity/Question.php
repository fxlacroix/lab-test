<?php

namespace FXL\Bundle\QCMBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use FXL\Component\Entity\AttachedDate;
use Doctrine\Common\Collections\ArrayCollection;

/**
 *
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity()
 * @ORM\Table(name="qcm__question")
 */
class Question extends AttachedDate
{
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $content;

    /**
     * @ORM\ManyToOne(targetEntity="Node")
     * @ORM\JoinColumn(name="node_id", referencedColumnName="id")
     */
    private $node;

    /**
     * @ORM\OneToMany(targetEntity="Answer", mappedBy="question", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="id", referencedColumnName="question_id", nullable=true)
     * @ORM\OrderBy({"updatedAt" = "ASC"})
     */
    private $answers;

     /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $time;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isOpened;

    /**
     * @ORM\ManyToMany(targetEntity="Review", mappedBy="questions", cascade={"persist"})
     */
    private $reviews;

    public function __toString(){

        return $this->content."";
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->answers = new \Doctrine\Common\Collections\ArrayCollection();
        $this->reviews = new \Doctrine\Common\Collections\ArrayCollection();

    }

    /**
     * Set content
     *
     * @param string $content
     * @return Question
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set time
     *
     * @param integer $time
     * @return Question
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
     * Set node
     *
     * @param \FXL\Bundle\QCMBundle\Entity\Node $node
     * @return Question
     */
    public function setNode(\FXL\Bundle\QCMBundle\Entity\Node $node = null)
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

    /**
     * Add answers
     *
     * @param \FXL\Bundle\QCMBundle\Entity\Answer $answers
     * @return Question
     */
    public function addAnswer(\FXL\Bundle\QCMBundle\Entity\Answer $answers)
    {
        $this->answers[] = $answers;

        return $this;
    }

    /**
     * Remove answers
     *
     * @param \FXL\Bundle\QCMBundle\Entity\Answer $answers
     */
    public function removeAnswer(\FXL\Bundle\QCMBundle\Entity\Answer $answers)
    {
        $this->answers->removeElement($answers);
    }

     /**
     * set answers
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function setAnswers($answers)
    {
        $this->answers = new ArrayCollection($answers);

        return $this->answers;
    }


    /**
     * Get answers
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAnswers()
    {
        return $this->answers;
    }

    /**
     * find answers
     *
     * @return arrays
     */
    public function findAllAnswers()
    {
        $answers = array();

        foreach($this->answers as $answer){
            $answers[] = $answer;
        }
        return $answers;
    }

    /**
     * Add reviews
     *
     * @param \FXL\Bundle\QCMBundle\Entity\Review $reviews
     * @return Question
     */
    public function addReview(\FXL\Bundle\QCMBundle\Entity\Review $reviews)
    {
        $this->reviews[] = $reviews;

        return $this;
    }

    /**
     * Remove $reviews
     *
     * @param \FXL\Bundle\QCMBundle\Entity\Review $reviews
     */
    public function removeReview(\FXL\Bundle\QCMBundle\Entity\Review $reviews)
    {
        $this->reviews->removeElement($reviews);
    }

    /**
     * Get reviews
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getReviews()
    {
        return $this->reviews;
    }

    public function getIsOpened() {
        return $this->isOpened;
    }

    public function setIsOpened($isOpened) {
        $this->isOpened = $isOpened;
    }


}