<?php

namespace Pan100\MoodLogBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use FOS\UserBundle\Doctrine\UserManager;

use Pan100\MoodLogBundle\Entity\Day;
use Pan100\MoodLogBundle\Entity\Medication;
use Pan100\MoodLogBundle\Entity\Trigger;

class CreateMockDataCommand extends ContainerAwareCommand
{
    private $userManager;

    protected function configure()
    {
        $this
            ->setName('mood:mockdata')
            ->setDescription('Creates mockdata for testing')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $this->userManager = $this->getContainer()->get('fos_user.user_manager');

        $output->writeln("<info>This command creates a few testusers for the MoodLogBundle</info>");
        $confirm = $this->getHelperSet()->get('dialog')->ask($output, "<question>Are you sure? Y or N - </question>");

        if($confirm == "Y" || $confirm == "y") {
            $output->writeln("<info>Creating user with name \"Ola_Nordmann\"as a patient...</info>");
            $user = $this->createUser("Ola_Nordmann", "olanord@mockdata.no", "passord", false, $output);
            $output->writeln("<info>Creating mockdata for patient with name \"Ola_Nordmann\"</info>");

            //find the date for 14 days ago
            $dateTwoWeeksAgo = new \DateTime();
            $dateTwoWeeksAgo->sub(new \DateInterval("P14D"));

            for($i = 1; $i <= 14; $i++) {
                $output->writeln("<info>Adding Day entities for " . $dateTwoWeeksAgo->format("F j, Y") . "</info>");
                //add Day entry with data

                $em = $this->getContainer()->get('doctrine')->getEntityManager();

                $day = new Day();
                $day->setDate($dateTwoWeeksAgo);
                //random number for the high
                $moodHigh = rand(0,100);
                //moodLow must be lower
                $moodLow = rand(0, $moodHigh);

                $day->setMoodHigh($moodHigh);
                $day->setMoodLow($moodLow);
                $day->setSleepHours(rand(0,20));
                $day->setAnxiety(rand(0,3));
                $day->setIrritability(rand(0,3));
                $day->setDiaryText("Just as yesterday - did nothing");
                //add medicines - remember always lower case
                $medObj = $this->getContainer()->get('doctrine')->getRepository('Pan100\MoodLogBundle\Entity\Medication')->findOneBy(array('name' => 'lithium'));
                    //check if the medicine exists and create it otherwise
                if($medObj == null) {
                    $med = new Medication();
                    $med->setName("Lithium");
                    $med->setAmountMg(100);
                    $medObj = $med;
                    $em->persist($medObj);
                }

                $day->addMedication($medObj);

                $day->setUserId($user);
                //add triggers randomly
                if($triggerString = $this->returnRandomTrigger($output)) {
                    $triggerObject = $this->getContainer()->get('doctrine')->getRepository('Pan100\MoodLogBundle\Entity\Trigger')->findOneBy(array('triggertext' => $triggerString));
                    $output->writeln("<info>Hit random trigger lottery</info>");
                    if($triggerObject == null) {
                        $trigger = new Trigger();
                        $trigger->setTriggertext($triggerString);
                        $em->persist($trigger);
                        $day->addTrigger($trigger);                        
                    }
                } 

                //persist
                $em->persist($day);
                $em->flush();               
                //remember to set date one day ahead
                $dateTwoWeeksAgo->modify('+1 day');
            }

            $output->writeln("<info>Creating user with name \"drjones\"as a medic...</info>");
            $user2 = $this->createUser("drjones", "drjones@mockdata.no", "passord", true, $output);
            $output->writeln("<info>Linking \"drjones\" with \"Ola_Nordmann\"</info>");
            $user->addHasAccessToMe($user2);
            $this->userManager->updateUser($user);

        }
        elseif($confirm == "N" || $confirm == "n") {
            $output->writeln("<error>Aborting as requested</error>");
        }
        else {
            $output->writeln("<error>wrong key. Then we abort in this version as I don't care for refactoring right now</error>");
        }
    }

    private function createUser($name, $eMail, $password, $isMedic, $output) {
            $command = $this->getApplication()->find('fos:user:create');

            $arguments = array(
                'command' => 'fos:user:create',
                'username'    => $name,
                'email' => $eMail,
                'password' => $password
            );
            $input = new ArrayInput($arguments);
            $returnCode = $command->run($input, $output);
                $user = $this->userManager->findUserByUsername($name);
                
                if($isMedic) {
                    $user->addRole("ROLE_MEDIC");
                }
                else {
                    $user->addRole("ROLE_PATIENT");
                }
                $this->userManager->updateUser($user);

                return $user;
    }

    private function returnRandomTrigger($outputStream) {
        $mockTriggers = array("chrush","argument","alcohol", "death in family");
        if(rand(0,5) == 1) {
            //return a random trigger
            $randomString = $mockTriggers[array_rand($mockTriggers)];
            $outputStream->writeln("<info>Returning " . $randomString . " from returnRandomTrigger()</info>");
            return  $randomString;

        }
        return false;
    }

}