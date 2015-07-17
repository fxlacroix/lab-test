<?php

namespace FXL\Bundle\GameBundle\Entity;

class Maze {

    public $height;
    public $width;
    public $level = array();

    public function __construct($width, $height){

        $this->width = $width;
        $this->height = $height;
    }

    public function generate(){

        $this->reset();
        $this->ajust();
    }

    public function ajust(){

    }

    public function reset(){
        for($i = 0; $i < $this->width; $i ++){
            for($j = 0; $j < $this->height; $j ++){
                $this->level[$i][$j] = rand(0, 1);
            }
        }
    }

} 