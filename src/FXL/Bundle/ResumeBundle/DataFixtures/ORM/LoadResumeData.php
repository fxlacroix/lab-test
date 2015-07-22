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
use Symfony\Component\Yaml\Parser;

class LoadResumeData implements FixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $yaml = new Parser();
        $data = $yaml->parse(file_get_contents(__DIR__.'/data.yaml'));
        $resumeParams = $data['resume'];

        // Resume
        $resume = new Resume();
        $resume->setName($resumeParams['name']);
        $resume->setDescription($resumeParams['description']);
        $resume->setYearsOfExperience($resumeParams['years_of_experience']);

        // Identity
        $identityParam = $resumeParams['identity'];
        $identity = new Identity();
        $identity->setFirstName($identityParam['first_name']);
        $identity->setLastName($identityParam['last_name']);
        $identity->setDateOfBirth($identityParam['date_of_birth']);
        $resume->setIdentity($identityParam);

        // Skills
        $skillParams = $resumeParams['skill'];
        foreach($skillParams as $skillName => $skillLevel){

            $skill = new Skill();
            $skill->setName($skillName);
            $skill->setLevel($skillLevel);
            $skill->setResume($resume);

            $resume->addSkill($skill);
        }

        // Studies
        $studyParams = $resumeParams['study'];
        foreach($studyParams as $studyParam){

            $study = new Study();
            $study->setName($studyParam['school']);
            $study->setResume($resume);

            $resume->addStudy($study);
        }

        // Experiences
        $experienceParams = $resumeParams['study'];
        foreach($experienceParams as $experienceParam){

            $experience = new Experience();
            $experience->setCompany($experienceParam['company']);
            $experience->setTitle($experienceParam['title']);
            $experience->setStartAt($experienceParam['start_at']);
            $experience->setResume($resume);

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