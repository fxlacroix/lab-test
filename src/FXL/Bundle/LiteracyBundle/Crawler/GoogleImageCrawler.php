<?php

namespace FXL\Bundle\LiteracyBundle\Crawler;

use FXL\Component\Crawler\BaseCrawler;

class GoogleImageCrawler extends BaseCrawler
{
    public function crawl($name)
    {
        $pattern = '#src="([^"]*)"></a><br><cite#';

        //http://www.google.fr/imgres?um=1&amp;hl=fr&amp;biw=1278&amp;bih=679&amp;tbm=isch&amp;tbnid=reArUoLV33TaEM:&amp;imgrefurl=http://fr.123rf.com/photo_11446766_vector-illustration-de-l-39-atome-colore.html&amp;docid=cnhXzL6JeWvjVM&amp;imgurl=http://us.cdn4.123rf.com/168nwm/yuliaglam/yuliaglam1201/yuliaglam120100001/11996658-atome.jpg&amp;w=168&amp;h=168&amp;ei=7L1gUcmPGIKrhAe87YCoBw&amp;zoom=1

        $pattern = "#imgurl=([^&]*)&#";

        $url="https://www.google.fr/search?hl=fr&q=".urlencode($name)."&um=1&ie=UTF-8&tbm=isch&tbs=isz:m";

        $content = file_get_contents($url);

        preg_match_all($pattern, $content, $matches);

        $s="";

        foreach($matches[1] as $match){
            $s .= "<img style='max-width: 100px; max-height: 100px;' src='". $match."' />&nbsp;";
        }

        return $s;
    }
}