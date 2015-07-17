<?php

namespace FXL\Bundle\MusicBundle\Twig\Extension;

use Symfony\Component\HttpKernel\KernelInterface;
use Twig_Extension;
use Twig_Filter_Method;

class MusicExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            'cutText' => new Twig_Filter_Method($this, 'cutText'),
            'getArrayChord' => new Twig_Filter_Method($this, 'getArrayChord'),
        );
    }

    public function getArrayChord($string)
    {
        $htmlTable = "";

        if($string) {

            $string = nl2br($string);
            $brs = preg_split("#<br />#", $string);

            $maxRow = 0;
            foreach($brs as $br) {

                $pipeSplit = preg_split("/\|/", $br);

                $maxRow = 0;
                foreach($pipeSplit as $key => $pipe) {
                    if($key > $maxRow) {
                        $maxRow = $key;
                    }
                }
            }
            $maxRow++;

            $htmlTable .= '<table class="table table-condensed table-bordered table-striped">';

            foreach($brs as $br) {

                $pipeSplit = preg_split("/\|/", $br);

                $htmlTable .= '<tr style="text-align: center">';

                $br = str_replace("<br />", "", $br);

                if(ltrim($br) == "") {

                    $htmlTable .= "<td colspan='$maxRow'>&nbsp;</td>";

                }else {

                    foreach($pipeSplit as $pipe) {

                        $htmlTable .= "<td>".$pipe."</td>";

                    }
                }

                $htmlTable .= "</tr>";
            }

            $htmlTable .= "</table>";

        }
        
        return str_replace("\\", "<br />", $htmlTable);
    }

    public function cutText($string)
    {
        $total = strlen($string);
        $parts = array();
        $part = "";
        $pageSize = 20;
        $line = $lastLine = $lastParagraph = 0;

        for($i = 0; $i < $total; $i++) {

            if($string[$i] == "<") {

//                echo (int)(($i - $lastLine) / 60)." ".$line.'<br />';

                $line = $line + 1 + (int)(($i - $lastLine) / 75);

                $lastLine = $i;



                if($line % $pageSize == 0) {

                    $pageSize = 22;

                    $parts[] =  $part;
                    $part = "";
                    $line = 0;
                    $i += 6;
                    $lastParagraph = $i;

                } else {
                    $part .= $string[$i];
                }

            }else{

                $part .= $string[$i];
            }
        }

        if($lastParagraph < $total) {

            $parts[] = substr($string, $lastParagraph, $total - $lastParagraph);
        }

        return $parts;
    }

    public function getName()
    {
        return 'twig.extension.nl1br';
    }
}