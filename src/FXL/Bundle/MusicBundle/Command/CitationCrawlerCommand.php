<?php

namespace FXL\Bundle\MusicBundle\Command;

use Symfony\Component\Console\Input\InputInterface,
    Symfony\Component\Console\Output\OutputInterface;

use FXL\Bundle\MusicBundle\Entity\Citation;

/**
 * Crawl citations
 */
class CitationCrawlerCommand extends FXLContainerAwareCommand
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
            ->setName('fxlmusic:crawl:citation')
            ->setDescription('Crawl and save citation');
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

        for($i=1; $i<= 248; $i ++){

            $url = 'http://www.evene.fr/citations/citation-jour.php?page='.$i;

            $s = file_get_contents($url);

            preg_match_all('/<li class="last">(.*)<\/li>/', $s, $htmlCitations);

            foreach($htmlCitations[1] as $htmlCitation) {

                preg_match('/data-text="([^"]*)"/', $htmlCitation, $citationAuthor);

                if(! empty($citationAuthor[1])) {

                    $citation = preg_split("/ - /",$citationAuthor[1]);

                    if(isset($citation[1])){

                        $existingCitation = $this->getContainer()
                            ->get('doctrine')
                            ->getEntityManager()
                            ->getRepository("FXLMusicBundle:Citation")
                            ->findByContent($citation[1]);

                        if(!$existingCitation){
                            $ocitation = new Citation();

                            $ocitation->setAuthor($citation[0]);
                            $ocitation->setContent($citation[1]);


                            $em->persist($ocitation);

                            echo $citation[0]." - ".$citation[1]." \n";
                        }else{

                            echo "already exit  \n";
                        }
                    }
                }

            }

            $em->flush();

            echo "done page ".$i." \n";
        }
    }
}
