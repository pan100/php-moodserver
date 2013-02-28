<?php

namespace Pan100\MoodLogBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use FOS\UserBundle\Doctrine\UserManager;
use FOS\UserBundle\Doctrine\GroupManager;

class CreateMockDataCommand extends ContainerAwareCommand
{
    private $userManager;
    private $groupManager;

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
        $this->groupManager = $this->getContainer()->get('fos_user.group_manager');

        $output->writeln("<info>This command creates a few testusers for the MoodLogBundle</info>");
        $confirm = $this->getHelperSet()->get('dialog')->ask($output, "<question>Are you sure? Y or N - </question>");

        if($confirm == "Y" || $confirm == "y") {
            $output->writeln("<info>Creating mockdata patient with name \"Ola Nordmann\"as a patient...</info>");
            $this->createUsers("Ola_Nordmann", "olanord@mockdata.no", "passord", false, $output);
        }
        elseif($confirm == "N" || $confirm == "n") {
            $output->writeln("<error>Aborting as requested</error>");
        }
    }

    private function createUsers($name, $eMail, $password, $isMedic, $output) {
            $command = $this->getApplication()->find('fos:user:create');

            $arguments = array(
                'command' => 'fos:user:create',
                'username'    => $name,
                'email' => $eMail,
                'password' => $password
            );
            $input = new ArrayInput($arguments);
            $returnCode = $command->run($input, $output);

            //retrieve the user and add it to the Patient group
            //DONT KNOW IF THIS WORKS YET
                $user = $this->userManager->findUserByUsername($name);
//                $group = $this->groupManager->findGroupByName("Patient");
                $user->addRole("ROLE_MEDIC");
                if($isMedic) {
//                    $group = $this->userManager->findGroupByName("Medic");
                    $user->addRole("ROLE_PATIENT");
                }
//                $user->addGroup($group);
                $this->userManager->updateUser($user);
    }
}