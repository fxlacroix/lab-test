<?php

namespace FXL\Bundle\QCMBundle\Entity;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Doctrine\ORM\Mapping as ORM;
use FXL\Component\Entity\Base\AttachedDate;

/**
 *
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity()
 * @ORM\Table(name="qcm__qcm")
 */
class QCM extends AttachedDate
{

    /**
     * @ORM\ManyToOne(targetEntity="\FXL\Bundle\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=true)
     */
    protected $user;

    /**
     * @ORM\OneToMany(targetEntity="Node", mappedBy="qcm", cascade={"persist", "remove"}, fetch="EAGER")
     * @ORM\JoinColumn(name="id", referencedColumnName="qcm_id", nullable=true)
     */
    private $nodes;

    /**
     * @ORM\OneToMany(targetEntity="Review", mappedBy="qcm", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="id", referencedColumnName="qcm_id", nullable=true)
     */
    private $reviews;

     /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $time;

    /**
     * @ORM\Column(name="published", type="boolean", nullable=true)
     */
    protected $published = false;

    /**
     * @ORM\Column(name="email", type="string", nullable=true)
     */
    protected $email;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $url;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->nodes = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set time
     *
     * @param integer $time
     * @return QCM
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
     * Add nodes
     *
     * @param \FXL\Bundle\QCMBundle\Entity\Node $nodes
     * @return QCM
     */
    public function addNode(\FXL\Bundle\QCMBundle\Entity\Node $nodes)
    {
        $this->nodes[] = $nodes;

        return $this;
    }

    /**
     * Remove nodes
     *
     * @param \FXL\Bundle\QCMBundle\Entity\Node $nodes
     */
    public function removeNode(\FXL\Bundle\QCMBundle\Entity\Node $nodes)
    {
        $this->nodes->removeElement($nodes);
    }

    /**
     * Get nodes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getNodes()
    {
        return $this->nodes;
    }


    /**
     * Add review
     *
     * @param \FXL\Bundle\QCMBundle\Entity\Reviews $reviews
     *
     * @return Review
     */
    public function addReview(\FXL\Bundle\QCMBundle\Entity\Review $reviews)
    {
        $this->reviews[] = $reviews;

        return $this;
    }

    /**
     * Remove reviews
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


    /**
     * get user
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * set user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * get published
     *
     * @return type
     */
    public function getPublished() {
        return $this->published;
    }

    /**
     * set published
     *
     * @param boolean $published
     */
    public function setPublished($published) {
        $this->published = $published;
    }

    public function getEmail() {
        return $this->email;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function setNodes($nodes) {
        $this->nodes = $nodes;
    }

    public function setReviews($reviews) {
        $this->reviews = $reviews;
    }


    /**
     *
     */
    public function findAllQuestions() {

        $questions = array();
        foreach($this->getNodes() as $node){

            $questions = array_merge($questions, $node->findQuestions());
        }

        return $questions;
    }

    /**
     * count questions
     *
     * @return QuestionCollection
     */
    public function countQuestions() {

        $total = 0;

        foreach($this->getNodes() as $node){

            $total += $node->countQuestions();
        }

        return $total;
    }

    /**
     * count nodes with question directly inside
     *
     * @return array
     */
    public function getNodesWithQuestion() {

        $subjects = array();

        foreach($this->getNodes() as $node){

            $subjects = array_merge($node->getNodesWithQuestion(), $subjects);
        }

        return array_unique($subjects);
    }

    /**
     * @ORM\PreUpdate
     * @ORM\PrePersist
     */
    public function preSave()
    {
        // the file property can be empty if the field is not required
       if (null === $this->file) {
            if($this->url && !$this->path) {

                $urlPath = basename(parse_url($this->url, PHP_URL_PATH));

                if(!file_exists($this->getUploadRootDir())) {
                    mkdir($this->getUploadRootDir(), 0777);
                }

                $uri = $this->getUploadRootDir().$urlPath;

                $fileContent = file_get_contents($this->url);

                file_put_contents($uri, $fileContent);

                $this->path = $this->getUploadDir().$urlPath;

                return;

            }
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

    public function getUrl(){
        return $this->url;
    }

    public function setUrl($url){
       $this->url = $url;
    }

}