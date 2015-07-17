<?php

namespace FXL\Bundle\PhotoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="meta")
 */
class Meta
{
  /**
   * @ORM\Id
   * @ORM\Column(type="integer")
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  protected $id;
  /**
   * @ORM\Column(type="string", length=100)
   */
  protected $username;
  /**
   * @ORM\Column(type="string", length=100)
   */
  protected $hook;
  /**
   * @ORM\Column(type="string", length=100)
   */
  protected $title1;
  /**
   * @ORM\Column(type="text")
   */
  protected $text1;
  /**
   * @ORM\Column(type="string", length=100)
   */
  protected $title2;
  /**
   * @ORM\Column(type="text")
   */
  protected $text2;
  /**
   * @ORM\Column(type="string", length=100)
   */
  protected $title3;
  /**
   * @ORM\Column(type="text")
   */
  protected $text3;

  /**
   * @param mixed $text1
   */
  public function setText1($text1)
  {
    $this->text1 = $text1;
  }

  /**
   * @return mixed
   */
  public function getText1()
  {
    return $this->text1;
  }

  /**
   * @param mixed $text2
   */
  public function setText2($text2)
  {
    $this->text2 = $text2;
  }

  /**
   * @return mixed
   */
  public function getText2()
  {
    return $this->text2;
  }

  /**
   * @param mixed $text3
   */
  public function setText3($text3)
  {
    $this->text3 = $text3;
  }

  /**
   * @return mixed
   */
  public function getText3()
  {
    return $this->text3;
  }

  /**
   * @param mixed $title1
   */
  public function setTitle1($title1)
  {
    $this->title1 = $title1;
  }

  /**
   * @return mixed
   */
  public function getTitle1()
  {
    return $this->title1;
  }

  /**
   * @param mixed $title2
   */
  public function setTitle2($title2)
  {
    $this->title2 = $title2;
  }

  /**
   * @return mixed
   */
  public function getTitle2()
  {
    return $this->title2;
  }

  /**
   * @param mixed $title3
   */
  public function setTitle3($title3)
  {
    $this->title3 = $title3;
  }

  /**
   * @return mixed
   */
  public function getTitle3()
  {
    return $this->title3;
  }

  /**
   * @param mixed $hook
   */
  public function setHook($hook)
  {
    $this->hook = $hook;
  }

  /**
   * @return mixed
   */
  public function getHook()
  {
    return $this->hook;
  }


}