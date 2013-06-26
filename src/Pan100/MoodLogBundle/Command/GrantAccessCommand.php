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

class GrantAccessCommand extends ContainerAwareCommand
{
    private $userManager;

    protected function configure()
    {
        $this
            ->setName('mood:grantaccess')
            ->setDescription('grants a medic access to reports of the given patient')
            ->addArgument('medic', InputArgument::REQUIRED, 'Type the username of the medic')
            ->addArgument('patient', InputArgument::REQUIRED, 'Type the username of the patient')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->userManager = $this->getContainer()->get('fos_user.user_manager');

            $medic = $this->userManager->findUserByUsername($input->getArgument('medic'));
            $patient = $this->userManager->findUserByUsername($input->getArgument('patient'));
            $patient->addHasAccessToMe($medic);
            $this->userManager->updateUser($patient);
    }
}