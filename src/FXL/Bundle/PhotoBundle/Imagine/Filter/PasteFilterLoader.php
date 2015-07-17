<?php

namespace FXL\Bundle\PhotoBundle\Imagine\Filter;

use Avalanche\Bundle\ImagineBundle\Imagine\Filter\Loader\LoaderInterface;
use Imagine\Filter\Basic\Paste;
use Imagine\Gd\Imagine;
use Imagine\Image\Point;

class PasteFilterLoader implements LoaderInterface
{
    public function load(array $options = array())
    {
        //$imgSource = $this->imagine->open($sourcePath);

        $point = new Point($options['x'], $options['y']);
        $imagine = new Imagine();
        //$image = $imagine->load(file_get_contents($options['image']));

        $image = $this->getImage();
        $image = $imagine->load($image);

        return new Paste($image, $point);
    }

    public function getImage(){
        $im = imagecreatetruecolor(280, 30);

        imagealphablending($im, false);
        $col=imagecolorallocatealpha($im,255,255,255,127);
        imagefilledrectangle($im,0,0,280, 100,$col);
        imagealphablending($im,true);

        $transparent = imagecolorallocatealpha($im, 200, 200, 200, 120);
        //imagefilledrectangle($im, 0, 0, 50, 129, $transparent);
        $text = 'Photo protégée par copyright';
        $font = '/Library/Fonts/Arial.ttf';
        imagettftext($im, 15, 0, 11, 21, $transparent, $font, $text);
        imagettftext($im, 15, 0, 10, 20, $transparent, $font, $text);


        ob_start();
        imagepng($im);
        $image = ob_get_contents();
        ob_end_clean();
        imagedestroy($im);

        return $image;


        $transparent = imagecolorallocatealpha($im, 200, 200, 200, 120);
        imagefilledrectangle($im, 0, 0, 50, 129, $transparent);
        $text = 'Photo protégé par copyright';
        $font = '/Library/Fonts/Arial.ttf';
        imagettftext($im, 15, -12, 11, 21, $transparent, $font, $text);
        imagettftext($im, 15, -12, 10, 20, $transparent, $font, $text);
        ob_start();
        imagepng($im);
        $image = ob_get_contents();
        ob_end_clean();
        imagedestroy($im);

        return $image;
    }
}
