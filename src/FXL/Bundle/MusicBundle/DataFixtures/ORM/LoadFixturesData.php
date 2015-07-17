<?php
namespace FXL\Bundle\MusicBundle\DataFixtures\ORM;

use Symfony\Component\Yaml\Parser;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use FXL\Bundle\MusicBundle\Entity\Project;
use FXL\Bundle\MusicBundle\Entity\Track;
use FXL\Bundle\MusicBundle\Entity\Document;


class LoadFixturesData implements FixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {

        $dataProjects = $this->getDataFixtures();

        foreach($dataProjects as $dataProject) {

            $project = new Project();
            $project->setName($dataProject['name']);
            $project->setDescription($dataProject['description']);
            $project->setContent($dataProject['content']);
            $project->setLogo($dataProject['logo']);

            foreach($dataProject['tracks'] as $dataTrack) {

                $track = new Track();
                $track->setName($dataTrack['name']);

                if(isset($dataTrack['content'])) {

                    $track->setContent($dataTrack['content']);
                }
                //$track->setNumber($dataTrack['number']);

                if(isset($dataTrack['documents'])) {

                    foreach($dataTrack['documents'] as $dataDocument) {

                        $document = new Document();
                        $document->setName($dataTrack['name']);
                        $document->setVersion($dataDocument['version']);
                        $document->setTrack($track);
                    }
                }

                $track->addDocument($document);
                $track->setProject($project);

                $project->addTrack($track);
            }

            $manager->persist($project);
            $manager->flush();
        }
    }

    public function getDataFixtures() {

        $yaml = new Parser();

        $fixtures = array();
        $fixtures = array_merge($fixtures, $yaml->parse(file_get_contents(__DIR__.'/old.yml')));
        $fixtures = array_merge($fixtures, $yaml->parse(file_get_contents(__DIR__.'/rocklisa.yml')));
        $fixtures = array_merge($fixtures, $yaml->parse(file_get_contents(__DIR__.'/marrebach.yml')));
        $fixtures = array_merge($fixtures, $yaml->parse(file_get_contents(__DIR__.'/pianobar.yml')));
        $fixtures = array_merge($fixtures, $yaml->parse(file_get_contents(__DIR__.'/cretinoide.yml')));
        $fixtures = array_merge($fixtures, $yaml->parse(file_get_contents(__DIR__.'/thaumaturge.yml')));
        $fixtures = array_merge($fixtures, $yaml->parse(file_get_contents(__DIR__.'/brassenserie.yml')));
        $fixtures = array_merge($fixtures, $yaml->parse(file_get_contents(__DIR__.'/variations.yml')));
        $fixtures = array_merge($fixtures, $yaml->parse(file_get_contents(__DIR__.'/sorcier.yml')));
        $fixtures = array_merge($fixtures, $yaml->parse(file_get_contents(__DIR__.'/rocklegend.yml')));
        $fixtures = array_merge($fixtures, $yaml->parse(file_get_contents(__DIR__.'/bandealouison.yml')));
        $fixtures = array_merge($fixtures, $yaml->parse(file_get_contents(__DIR__.'/thekid.yml')));
        $fixtures = array_merge($fixtures, $yaml->parse(file_get_contents(__DIR__.'/lefilsdejohn.yml')));

        return $fixtures;

    }
}