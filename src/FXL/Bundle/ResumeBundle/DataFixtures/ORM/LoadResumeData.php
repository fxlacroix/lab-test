<?php

namespace FXL\Bundle\ResumeBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use FXL\Bundle\ResumeBundle\Entity\Leisure;
use FXL\Bundle\ResumeBundle\Entity\Resume;
use FXL\Bundle\ResumeBundle\Entity\Identity;
use FXL\Bundle\ResumeBundle\Entity\Skill;
use FXL\Bundle\ResumeBundle\Entity\Experience;
use FXL\Bundle\ResumeBundle\Entity\Study;
use FXL\Bundle\ResumeBundle\Entity\Tag;
use FXL\Bundle\ResumeBundle\Entity\Task;
use FXL\Bundle\ResumeBundle\Entity\Trump;
use Symfony\Component\Yaml\Parser;

class LoadResumeData implements FixtureInterface
{
    static $tags = array();

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $yaml           = new Parser();
        $data           = $yaml->parse(file_get_contents(__DIR__.'/data.yaml'));
        $resumeParams   = $data['resume'];
        $tagParams      = $data['tag'];

        // Tag
        foreach($tagParams as $tagParams){
            $tag = new Tag();
            $tag->setId($tagParams['id']);
            $tag->setName($tagParams['name']);
            $tag->setWeight($tagParams['weight']);
            $manager->persist($tag);

            $tags[$tag->getId()] = $tag;
            $manager->flush();
        }

        // Resume
        $resume = new Resume();
        $resume->setName($resumeParams['name']);
        $resume->setDescription($resumeParams['description']);
        $resume->setYearsOfExperience($resumeParams['years_of_experience']);
        $resume->setLanguage($resumeParams['language']);

        // Identity
        $identityParam = $resumeParams['identity'];
        $identity = new Identity();
        $identity->setFirstName($identityParam['first_name']);
        $identity->setLastName($identityParam['last_name']);
        $identity->setDateOfBirth(new \DateTime($identityParam['date_of_birth']));
        $identity->setEmail($identityParam['email']);
        $identity->setResume($resume);
        $resume->setIdentity($identity);

        // Trump
        $trumpParams = $resumeParams['trump'];
        foreach($trumpParams as $trumpParam){
            $trump = new Trump();
            $trump->setName($trumpParam['name']);
            $trump->setResume($resume);
            $resume->addTrump($trump);
        }

        $manager->persist($resume);
        $manager->flush();
        // Skills
        $skillParams = $resumeParams['skill'];
        foreach($skillParams as $skillName => $skillParam){

            $skill = new Skill();
            $skill->setName($skillName);
            $skill->setLevel($skillParam['level']);
            $skill->setResume($resume);
            $resume->addSkill($skill);
            $manager->persist($skill);
            $manager->flush();

            $skill->setTag($tags[$skillParam['tag_id']]);
            $tags[$skillParam['tag_id']]->addSkill($skill);
            $manager->persist($skill);
            $manager->persist($tags[$skillParam['tag_id']]);
            $manager->flush();

        }



        // Studies
        $studyParams = $resumeParams['study'];
        foreach($studyParams as $studyParam){

            $study = new Study();
            $study->setName($studyParam['school']);
            $study->setStartAt(new \DateTime($studyParam['start_at']));
            $study->setFinishAt(new \DateTime($studyParam['finish_at']));
            $study->setDescription($studyParam['description']);
            $study->setResume($resume);
            $resume->addStudy($study);
        }

        // Experiences
        $experienceParams = $resumeParams['experience'];
        foreach($experienceParams as $experienceParam){

            $experience = new Experience();
            $experience->setCompany($experienceParam['company']);
            $experience->setDescription($experienceParam['description']);
            $experience->setWebsite($experienceParam['website']);
            $experience->setType($experienceParam['type']);
            $experience->setCity($experienceParam['city']);
            $experience->setTitle($experienceParam['title']);
            $experience->setStartAt(new \DateTime($experienceParam['start_at']));
            $experience->setResume($resume);

            foreach($experienceParam['tasks'] as $taskParam){

                $task = new Task();
                $task->setName($taskParam);
                $task->setExperience($experience);
                $experience->addTask($task);
            }

            $resume->addExperience($experience);
        }

        // Leisures
        $leisureParams = $resumeParams['leisure'];
        foreach($leisureParams as $leisureName){

            $leisure = new Leisure();
            $leisure->setName($leisureName);
            $leisure->setResume($resume);

            $resume->addLeisure($leisure);
        }

        $manager->persist($resume);
        $manager->flush();
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 1; // l'ordre dans lequel les fichiers sont chargés
    }
}