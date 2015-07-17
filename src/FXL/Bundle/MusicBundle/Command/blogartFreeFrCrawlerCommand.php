<?php

namespace FXL\Bundle\MusicBundle\Command;

use Symfony\Component\Console\Input\InputInterface,
    Symfony\Component\Console\Output\OutputInterface;

/**
 * Crawl songs
 */
class BlogartFreeFrCrawlerCommand extends FXLContainerAwareCommand
{
    /**
     * Configures the current command.
     *
     * @return void
     *
     * @see Symfony\Component\Console\Command\Command::configure()
     */
    protected function configure()
    {
        $this
            ->setName('fxlmusic:crawl:blogart-free-fr')
            ->setDescription('Crawl and save songs for blogart free fr');
    }

    /**
     * Executes the current command.
     *
     * @param InputInterface  $input  An InputInterface instance
     * @param OutputInterface $output An OutputInterface instance
     *
     * @return integer 0 if everything went fine, or an error code
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getEntityManager();

        $baseUrl = 'http://blogart.free.fr/musique/';
        $file = file_get_contents($baseUrl);
        $pattern = '#<A HREF="(.*)/">.*\. (.*) \(.*</A>#';

        preg_match_all($pattern, $file, $matches);

        $projects = $matches[1];
        $titles = $matches[2];

        foreach ($projects as $key => $project) {

            $fileProject = file_get_contents($baseUrl.$project);
            $patternFile = '#<A HREF="(.*)">(.*.mp3)</A>#';
            preg_match_all($patternFile, $fileProject, $matchesSongs);

            foreach($matchesSongs[1] as $key2 => $song) {

                $fileSong = $baseUrl.$project."/".$song;
                $folderBlogart = "/Users/fxlacroix/Sites/blogart/web/sounds/".$titles[$key]."/".$song;

                if(! file_exists("/Users/fxlacroix/Sites/blogart/web/sounds/".$titles[$key])) {

                    mkdir("/Users/fxlacroix/Sites/blogart/web/sounds/".$titles[$key], 0777, true);
                }

                if(! file_exists($fileSong)) {

                    copy($fileSong, $folderBlogart);
                }

                echo "done ".$song."\n";

            }


            echo "done ".$titles[$key]."\n";
        }
        //$em->flush();
    }
}
