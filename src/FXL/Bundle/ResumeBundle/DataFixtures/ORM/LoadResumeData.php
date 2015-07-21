<?php

namespace FXL\Bundle\ResumeBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use FXL\Bundle\ResumeBundle\Entity\Resume;
use FXL\Bundle\ResumeBundle\Entity\Identity;
use FXL\Bundle\ResumeBundle\Entity\Skill;
use FXL\Bundle\ResumeBundle\Entity\Experience;
use FXL\Bundle\ResumeBundle\Entity\Study;

class LoadResumeData implements FixtureInterface
{
    static public $skills = array(
        'PHP'           =>  'Expert',
        'MySQL'         =>  'Expert',
        'JavaScript'    =>  'Expert'
    );

    static public $experiences = array(
        array(
            'company'   =>  'Qobuz',
            'start_at'   =>  '2012-06-01'
        ),
        array(
            'company'   =>  'Alten',
            'start_at'   =>  '2009-02-01'
        ),
        array(
            'company'   =>  'Clever-Age',
            'start_at'   =>  '2005-06-01'
        ),
    );

    static public $studies = array(
        array(
            'school'   =>  'EFREI',
        ),
        array(
            'school'   =>  'Saint Thomas de Villeneuve',
        ),
    );
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        // Resume
        $resume = new Resume();
        $resume->setName('Ingénieur Informatique - spécialisé Web');
        $resume->setDescription('Issu du milieu open source bla bla');

        // Identity
        $identity = new Identity();
        $identity->setFirstName('François-Xavier');
        $identity->setLastName('LACROIX');
        $resume->setIdentity($identity);

        // Skills
        foreach(self::$skills as $skillName => $skillLevel){

            $skill = new Skill();
            $skill->setName($skillName);
            $skill->setLevel($skillLevel);
            $skill->setResume($resume);

            $resume->addSkill($skill);
        }

        // Studies
        foreach(self::$studies as $studyParam){

            $study = new Study();
            $study->setName($studyParam['school']);
            $study->setResume($resume);

            $resume->addStudy($study);
        }

        // Experiences
        foreach(self::$experiences as $experienceParam){

            $experience = new Experience();
            $experience->setCompany($experienceParam['company']);
            $experience->setStartAt(new \DateTime($experienceParam['start_at']));
            $experience->setResume($resume);

            $resume->addExperience($experience);
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