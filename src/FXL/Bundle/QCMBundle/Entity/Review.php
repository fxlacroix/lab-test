<?php

namespace FXL\Bundle\QCMBundle\Entity;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Doctrine\ORM\Mapping as ORM;
use FXL\Component\Entity\Base\Base;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

/**
 *
 * @ORM\Entity(repositoryClass="FXL\Bundle\QCMBundle\Repository\ReviewRepository")
 * @ORM\Table(name="qcm__review")
 */
class Review extends Base
{
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @Assert\Email(
     *     message = "The email '{{ value }}' is not a valid email.",
     *     checkMX = true
     * )
     */
    protected $email;

    /**
     * @ORM\ManyToOne(targetEntity="QCM", fetch="EAGER")
     * @ORM\JoinColumn(name="qcm_id", referencedColumnName="id", nullable=true)
     */
    private $qcm;

    /**
     * @ORM\ManyToMany(targetEntity="Question", inversedBy="reviews", cascade={"persist"}, fetch="EAGER")
     * @ORM\JoinTable(name="qcm__review_question")
     */
    private $questions;

    /**
     * @ORM\ManyToMany(targetEntity="Answer", inversedBy="reviews", cascade={"persist"}, fetch="EAGER")
     * @ORM\JoinTable(name="qcm__review_answer")
     */
    protected $answers;

    /**
     * @ORM\Column(name="selection_question", type="integer", nullable=true)
     */
    protected $selectionQuestion = 5;

    /**
     * @ORM\Column(name="isCorrected", type="boolean", nullable=true)
     */
    protected $isCorrected = false;

    /* non model param */
    /**
     * show in admin tree
     */
    protected $hideInTree = true;
    /* integer */
    protected $minusPoint= 0;
    /* integer */
    protected $plusPoint= 0;
    /* integer */
    protected $score= 0;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->questions = new \Doctrine\Common\Collections\ArrayCollection();
        $this->answers   = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add questions
     *
     * @param \FXL\Bundle\QCMBundle\Entity\Question $questions
     * @return Review
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
     * set answers
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function setQuestions($questions)
    {
        $this->questions = new ArrayCollection($questions);

        return $this->questions;
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

    public function getQcm() {
        return $this->qcm;
    }

    public function setQcm($qcm) {
        $this->qcm = $qcm;
    }

    public function getEmail() {
        return $this->email;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    /**
     * get selection
     *
     * @return integer
     */
    public function getSelectionQuestion() {
        return $this->selectionQuestion;
    }

    /**
     * set selection
     *
     * @param integer $selection
     */
    public function setSelectionQuestion($selection) {
        $this->selectionQuestion = $selection;
    }

    public function getAnswers() {
        return $this->answers;
    }


    public function setAnswers($answers) {
        $this->answers = $answers;

        return $this;
    }


    public function getIsCorrected() {
        return $this->isCorrected;
    }

    public function setIsCorrected($corrected) {
        $this->isCorrected = $corrected;
    }

    public function getHideInTree() {
        return $this->hideInTree;
    }

    public function setHideInTree($hideInTree) {
        $this->hideInTree = $hideInTree;
    }

    public function getMinusPoint() {
        return $this->minusPoint;
    }

    public function setMinusPoint($minusPoint) {
        $this->minusPoint = $minusPoint;
    }

    public function getPlusPoint() {
        return $this->plusPoint;
    }

    public function setPlusPoint($plusPoint) {
        $this->plusPoint = $plusPoint;
    }

    public function getScore() {
        return $this->score;
    }

    public function setScore($score) {
        $this->score = $score;
    }

    /**
     * Add questions
     *
     * @param \FXL\Bundle\QCMBundle\Entity\Answer $answer
     * @return Review
     */
    public function addAnswer(\FXL\Bundle\QCMBundle\Entity\Answer $answers)
    {
        $this->answers[] = $answers;

        return $this;
    }

    /**
     * collect selected question
     */
    public function collectAnswer() {

        foreach($this->questions as $question){

            foreach($question->getAnswers() as $answer){

                if($answer->getSelected()){

                    $this->addAnswer($answer);
                }
            }
        }
    }

    /**
     * collect selected question
     */
    public function emend() {

        $total = $this->questions->count();
        foreach($this->answers as $answer){

            if($answer->getRight()){
                $this->plusPoint++;
            }else{
                $this->minusPoint++;
            }
        }

        $this->score = $this->plusPoint - $this->minusPoint;

        $rightAnswer = $this->countRightAnswer();

        if($rightAnswer){
            return ($this->plusPoint * 20 - $this->minusPoint * 3) / $rightAnswer ." / 20";
        }else{
            return "0 / 0 :|> !";
        }
    }


    /**
     * count right answer
     *
     * @return int
     */
    public function countRightAnswer(){

        $count = 0;
        foreach($this->getQuestions() as $question){

            foreach($question->getAnswers() as $answer){

                if($answer->getRight()){
                    $count++;
                }
            }
        }
        return $count;
    }

    public function getQcmReduce(){
        $name = $this->qcm->getName();

        return mb_substr($name, 0, 2)."..".mb_substr($this->qcm->getName(), mb_strlen($name) -3, mb_strlen($name));
    }

    public function getSlugReduce(){

        $slug = $this->getSlug();

        if(strlen($slug) < 13 ){

            return $slug;
        }

        $a = mb_strcut($slug, 0, 4);
        $b = mb_strcut($slug, mb_strlen($slug) - 5, mb_strlen($slug));

        return $a."..".$b;
    }


}