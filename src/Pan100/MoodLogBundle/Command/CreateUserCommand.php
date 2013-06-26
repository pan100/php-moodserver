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

class CreateUserCommand extends ContainerAwareCommand
{
    private $userManager;

    protected function configure()
    {
        $this
            ->setName('mood:createuser')
            ->setDescription('Creates a user')
            ->addArgument('username', InputArgument::REQUIRED, 'Type the username')
            ->addArgument('password', InputArgument::REQUIRED, 'Type the password')
            ->addOption('medic',null, InputOption::VALUE_NONE, 'set this flag if the user is a medical professional and wants access to patients but not log data')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $this->userManager = $this->getContainer()->get('fos_user.user_manager');
            $isMedic = false;
            if($input->getOption("medic")) {
                $isMedic = true;
            }
            $output->writeln("<info>Creating user with name \"" . $input->getArgument('username') . "\"as a patient...</info>");

            $user = $this->createUser($input->getArgument('username'), $this->get_random_string() . "@" . $this->get_random_string() . ".no", $input->getArgument('password'), $isMedic, $output);
            // $user->addHasAccessToMe($user2);
            // $this->userManager->updateUser($user);
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

    private function get_random_string($length = 5)   {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randomString;
    }
}