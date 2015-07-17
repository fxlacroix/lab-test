<?php

namespace FXL\Bundle\LiteracyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Definition
 *
 * @ORM\Table(name="lab__definition")
 * @ORM\Entity(repositoryClass="FXL\Bundle\LiteracyBundle\Repository\DefinitionRepository")
 */
class Definition
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text")
     */
    private $content;

    /**
     * @var string
     *
     * @ORM\Column(name="source", type="string", length=255)
     */
    private $source;

    /**
     * @ORM\ManyToOne(targetEntity="Word")
     * @ORM\JoinColumn(name="word_id", referencedColumnName="id", nullable=false)
     */
    protected $word;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set content
     *
     * @param string $content
     * @return Definition
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
     * Set source
     *
     * @param string $source
     * @return Definition
     */
    public function setSource($source)
    {
        $this->source = $source;

        return $this;
    }

    /**
     * Get source
     *
     * @return string
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * Set word
     *
     * @param \FXL\LiteracyBundle\Entity\Word $word
     * @return Definition
     */
    public function setWord(\FXL\Bundle\LiteracyBundle\Entity\Word $word = null)
    {
        $this->word = $word;

        return $this;
    }

    /**
     * Get word
     *
     * @return \FXL\LiteracyBundle\Entity\Word
     */
    public function getWord()
    {
        return $this->word;
    }

    public function __toString(){

        return $this->content;
    }
}