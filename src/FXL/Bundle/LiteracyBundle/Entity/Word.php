<?php

namespace FXL\Bundle\LiteracyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Gedmo\Mapping\Annotation as Gedmo; // gedmo annotations

/**
 * Word
 *
 * @ORM\Table(name="lab__word")
 * @ORM\Entity(repositoryClass="FXL\Bundle\LiteracyBundle\Repository\WordRepository")
 */
class Word
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="phonetic", type="string", length=255, nullable=true)
     */
    private $phonetic;

    /**
     * @var boolean
     *
     * @ORM\Column(name="definition_checked", type="boolean", nullable=true)
     */
    private $definitionChecked = false;

    /**
     * @var boolean
     *
     * @ORM\Column(name="anagram_checked", type="boolean", nullable=true)
     */
    private $anagramChecked = false;

    /**
     * @var boolean
     *
     * @ORM\Column(name="synonym_checked", type="boolean", nullable=true)
     */
    private $synonymChecked = false;

    /**
     * @var boolean
     *
     * @ORM\Column(name="rhyme_checked", type="boolean", nullable=true)
     */
    private $rhymeChecked = false;

    /**
     * @var boolean
     *
     * @ORM\Column(name="scrabble_checked", type="boolean", nullable=true)
     */
    private $scrabbleChecked = false;


    /**
     * @Gedmo\Slug(fields={"name"}, updatable=false, separator="-")
     * @ORM\Column(length=32, unique=true, nullable=true)
     */
    private $slug;

    /**
     * @ORM\OneToMany(targetEntity="Definition", mappedBy="word", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="id", referencedColumnName="word_id", nullable=true)
     */
    private $definitions;


    /**
     * @ORM\ManyToMany(targetEntity="Word", inversedBy="synonyms", cascade={"persist"})
     * @ORM\JoinTable(name="lab__W_S_W",
     *      joinColumns={@ORM\JoinColumn(name="word_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="word2_id", referencedColumnName="id")}
     *
     * )
     */
    private $synonyms;

    /**
     * @ORM\ManyToMany(targetEntity="Word", inversedBy="rhymes", cascade={"persist"})
     * @ORM\JoinTable(name="lab__W_R_W",
     *      joinColumns={@ORM\JoinColumn(name="word_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="word2_id", referencedColumnName="id")}
     * )
     */
    private $rhymes;

     /**
     * @ORM\ManyToMany(targetEntity="Word", inversedBy="scrabbles", cascade={"persist"})
     * @ORM\JoinTable(name="lab__W_Sc_W",
     *      joinColumns={@ORM\JoinColumn(name="word_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="word2_id", referencedColumnName="id")}
     * )
     */
    private $scrabbles;

     /**
     * @ORM\ManyToMany(targetEntity="Word", inversedBy="anagrams", cascade={"persist"})
     * @ORM\JoinTable(name="lab__W_A_W",
     *      joinColumns={@ORM\JoinColumn(name="word_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="word2_id", referencedColumnName="id")}
     * )
     */
    private $anagrams;


    public function exist(){

        return $this->id !== null;
    }

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
     * Set name
     *
     * @param string $name
     * @return Word
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return Word
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }


    public function getPhonetic()
    {
        return $this->phonetic;
    }

    public function setPhonetic($phonetic)
    {
        $this->phonetic = $phonetic;
    }


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->definitions = new \Doctrine\Common\Collections\ArrayCollection();
        $this->scrabbles= new \Doctrine\Common\Collections\ArrayCollection();
        $this->synonyms= new \Doctrine\Common\Collections\ArrayCollection();
        $this->rhymes= new \Doctrine\Common\Collections\ArrayCollection();

    }

    /**
     * Add definitions
     *
     * @param \FXL\LiteracyBundle\Entity\Definition $definitions
     * @return Word
     */
    public function addDefinition(\FXL\Bundle\LiteracyBundle\Entity\Definition $definition)
    {
        $definition->setWord($this);

        $this->definitions[] = $definition;

        return $this;
    }

    /**
     * Remove definitions
     *
     * @param \FXL\LiteracyBundle\Entity\Definition $definitions
     */
    public function removeDefinition(\FXL\Bundle\LiteracyBundle\Entity\Definition $definition)
    {
        $this->definitions->removeElement($definition);
    }

    /**
     * Get definitions
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDefinitions()
    {
        return $this->definitions;
    }

    /**
     * Add synonyms
     *
     * @param \FXL\LiteracyBundle\Entity\Word $synonyms
     * @return Word
     */
    public function addSynonym(\FXL\Bundle\LiteracyBundle\Entity\Word $synonyms)
    {
        if(! $this->synonyms->contains($synonyms) && $synonyms->getName() !== $this->name) {

            $this->synonyms[] = $synonyms;
        }

        return $this;
    }

    /**
     * Remove synonyms
     *
     * @param \FXL\LiteracyBundle\Entity\Word $synonyms
     */
    public function removeSynonym(\FXL\Bundle\LiteracyBundle\Entity\Word $synonyms)
    {
        $this->synonyms->removeElement($synonyms);
    }

    /**
     * Get synonyms
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSynonyms()
    {
        return $this->synonyms;
    }

    /**
     * Add synonyms
     *
     * @param \FXL\LiteracyBundle\Entity\Word $synonyms
     * @return Word
     */
    public function addScrabble(\FXL\Bundle\LiteracyBundle\Entity\Word $scrabbles)
    {
        if(! $this->scrabbles->contains($scrabbles) && $scrabbles->getName() !== $this->name) {

            $this->scrabbles[] = $scrabbles;
        }

        return $this;
    }

    /**
     * Remove synonyms
     *
     * @param \FXL\LiteracyBundle\Entity\Word $synonyms
     */
    public function removeScrabble(\FXL\Bundle\LiteracyBundle\Entity\Word $scrabbles)
    {
        $this->scrabbles->removeElement($scrabbles);
    }

    /**
     * Get synonyms
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getScrabbles()
    {
        return $this->scrabbles;
    }

    /**
     * Add anagrams
     *
     * @param \FXL\LiteracyBundle\Entity\Word $anagrams
     * @return Word
     */
    public function addAnagram(\FXL\Bundle\LiteracyBundle\Entity\Word $anagrams)
    {
        if(! $this->anagrams->contains($anagrams) && $anagrams->getName() !== $this->name) {

            $this->anagrams[] = $anagrams;
        }

        return $this;
    }

    /**
     * Remove anagrams
     *
     * @param \FXL\LiteracyBundle\Entity\Word $anagrams
     */
    public function removeAnagram(\FXL\Bundle\LiteracyBundle\Entity\Word $anagrams)
    {
        $this->anagrams->removeElement($anagrams);
    }

    /**
     * Get anagrams
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAnagrams()
    {
        return $this->anagrams;
    }

    /**
     * Add rhymes
     *
     * @param \FXL\LiteracyBundle\Entity\Word $rhymes
     * @return Word
     */
    public function addRhyme(\FXL\Bundle\LiteracyBundle\Entity\Word $rhymes)
    {
        if(! $this->rhymes->contains($rhymes) && $rhymes->getName() !== $this->name) {

            $this->rhymes[] = $rhymes;
        }

        return $this;
    }

    /**
     * Remove rhymes
     *
     * @param \FXL\LiteracyBundle\Entity\Word $rhymes
     */
    public function removeRhyme(\FXL\Bundle\LiteracyBundle\Entity\Word $rhymes)
    {
        $this->rhymes->removeElement($rhymes);
    }

    /**
     * Get rhymes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRhymes()
    {
        return $this->rhymes;
    }

    public function getDefinitionChecked()
    {
        return $this->definitionChecked;
    }

    public function setDefinitionChecked($definitionChecked)
    {
        $this->definitionChecked = $definitionChecked;
    }

    public function getSynonymChecked()
    {
        return $this->synonymChecked;
    }

    public function setSynonymChecked($synonymChecked)
    {
        $this->synonymChecked = $synonymChecked;
    }

    public function getRhymeChecked()
    {
        return $this->rhymeChecked;
    }

    public function setRhymeChecked($rhymeChecked)
    {
        $this->rhymeChecked = $rhymeChecked;
    }

    public function __toString(){

        return $this->name;
    }

    // very bad
    public function isChecked($prefixAttribute) {

        if(! in_array($prefixAttribute, array("definition", "synonym", "rhyme", "scrabble", "anagram"))) {

            throw new \InvalidArgumentException();
        }

        $attribute = "$prefixAttribute"."Checked";

        return $this->$attribute;

    }
    // very bad
    public function check($prefixAttribute) {

        if(! in_array($prefixAttribute, array("definition", "synonym", "rhyme", "scrabble", "anagram"))) {

            throw new \InvalidArgumentException();
        }

        $prefix = "$prefixAttribute"."Checked";

        $this->$prefix = true;

    }

    public function getScrabbleChecked()
    {
        return $this->scrabbleChecked;
    }

    public function setScrabbleChecked($scrabbleChecked)
    {
        $this->scrabbleChecked = $scrabbleChecked;
    }




}