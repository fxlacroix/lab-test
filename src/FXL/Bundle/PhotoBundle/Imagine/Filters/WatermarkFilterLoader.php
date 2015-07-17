<?php

namespace FXL\Bundle\PhotoBundle\Imagine\Filters\Loader;

use Imagine\Image\Box;
use Imagine\Image\ManipulatorInterface;
use Imagine\Filter\Basic\Thumbnail;

class WatermarkFilterLoader implements LoaderInterface
{
    public function load(array $options = array())
    {
        $mode = $options['mode'] === 'inset' ?
            ManipulatorInterface::THUMBNAIL_INSET :
            ManipulatorInterface::THUMBNAIL_OUTBOUND;

        list($width, $height) = $options['size'];

        $thumbnail = new Thumbnail(new Box($width, $height), $mode);


        $a  = 0;
    }
}
