<?php

namespace FXL\Bundle\MusicBundle\Command;

use Symfony\Component\Console\Input\InputInterface,
    Symfony\Component\Console\Output\OutputInterface;

/**
 * Crawl songs
 */
class BlogartMagazineCrawlerCommand extends FXLContainerAwareCommand
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
            ->setName('fxlmusic:crawl:blogart-magazine')
            ->setDescription('Crawl and save songs for blogart magazine');
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
        $projects = array(
            "Le-Thaumaturge",
            "Variations-de-Goldberg",
            "La-bande-a-Louison",
            "brassenserie",
            "Rock-Legend-Dream",
            "les-sorciers-du-verbe",
            "the-kid",
            "Le-fils-de-John"
        );

        //name="IMG/mp3/snowyMorningBlues.mp3"
        foreach($projects as $project){

            $projectUrl = "http://blogart-magazine.fr/".$project;

            $file = file_get_contents($projectUrl);

            $pattern = '#name="IMG/mp3/(.*).mp3"#';

            preg_match_all($pattern, $file, $matches);

            foreach($matches[1] as $match) {

                $urlMp3 = "http://blogart-magazine.fr/IMG/mp3/".$match.".mp3";

                $folderBlogart = "/Users/fxlacroix/Sites/blogart/web/sounds/".$project;
                $fileSong = $folderBlogart."/".$match.".mp3";

                if(! file_exists($folderBlogart)) {

                    mkdir($folderBlogart, 0777, true);
                }

                if(! file_exists($fileSong)) {

                    copy($urlMp3, $folderBlogart."/".$match.".mp3");
                }
            }
        }

    }
}
