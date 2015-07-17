<?php

namespace FXL\Bundle\PhotoBundle\Consumer\Driver;

use FXL\Component\Request\Curl;
use FXL\Component\XML\XPathDOMQuery;
use FXL\Component\Consumer\Driver\ConsumerDriver;

class RoyoDriver extends ConsumerDriver
{
    private $uri = "http://michel.royo.pagesperso-orange.fr/Carnet%20de%20voyages.htm";
    private $host = "http://michel.royo.pagesperso-orange.fr/";

    // driver royo

    public function search($term)
    {

    }

    public function find($id)
    {

        $googleImageHtmls = Curl::getInstance($this->uri)
            ->execute();

        $xpath = "//table[@width='75%']//a/@href";

        $domNodeUrls = XPathDOMQuery::get($googleImageHtmls)->query($xpath);
        $Urls = XPathDOMQuery::toArray($domNodeUrls);

        $xpath = "//table[@width='75%']//a";

        $domNodeUrls = XPathDOMQuery::get($googleImageHtmls)->query($xpath);
        $names = XPathDOMQuery::toArray($domNodeUrls);

        $images = $this->extractImages($Urls, $names);

        return $images;
    }


    public function extractImages($urls, $names)
    {

        $imagesNamed = array();
        foreach ($urls as $key => $url) {
            $royoFolder = Curl::getInstance($this->host . $url)
                ->execute();

            $xpath = "//img/@src";

            $domNodeUrls = XPathDOMQuery::get($royoFolder)->query($xpath);

            $images = XPathDOMQuery::toArray($domNodeUrls);

            foreach ($images as $image) {
                $imagesNamed[urldecode($names[$key])][] = $image;
            }

        }

        return $imagesNamed;
    }

}
