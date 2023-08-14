<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ChangeUserPasswordCommand extends Command
{
    private $passwordHasher;
    private $entityManager;

    public function __construct(UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager)
    {
        $this->passwordHasher = $passwordHasher;
        $this->entityManager = $entityManager;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('app:change-password')
            ->setDescription('Change a user\'s password')
            ->addArgument('id', InputArgument::REQUIRED, 'Id of the user')
            ->addArgument('newPassword', InputArgument::REQUIRED, 'New password');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $id = $input->getArgument('id');
        $newPassword = $input->getArgument('newPassword');

        $user = $this->entityManager->getRepository(User::class)->findOneBy(['id' => $id]);

        if (!$user) {
            $output->writeln(sprintf('User "%s" not found.', $id));
            return Command::FAILURE;
        }

        $encodedPassword = $this->passwordHasher->hashPassword($user, $newPassword);
        $user->setPassword($encodedPassword);

        $this->entityManager->flush();

        $output->writeln(sprintf('Password for user "%s" has been changed.', $id));

        return Command::SUCCESS;
    }
}
