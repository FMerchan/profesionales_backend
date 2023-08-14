<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;

class ManageUserRolesByIdCommand extends Command
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('app:manage-roles-by-id')
            ->setDescription('Add or remove roles from a user by ID')
            ->addArgument('userId', InputArgument::REQUIRED, 'ID of the user')
            ->addArgument('role', InputArgument::REQUIRED, 'Role to add or remove')
            ->addOption('add', null, InputOption::VALUE_NONE, 'Add the specified role')
            ->addOption('remove', null, InputOption::VALUE_NONE, 'Remove the specified role');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $userId = $input->getArgument('userId');
        $role = $input->getArgument('role');
        $addRole = $input->getOption('add');
        $removeRole = $input->getOption('remove');

        $user = $this->entityManager->getRepository(User::class)->find($userId);

        if (!$user) {
            $output->writeln(sprintf('User with ID "%s" not found.', $userId));
            return Command::FAILURE;
        }

        if ($addRole) {
            $user->addRole($role);
            $this->entityManager->flush();
            $output->writeln(sprintf('Role "%s" added to user with ID "%s".', $role, $userId));
        } elseif ($removeRole) {
            $user->removeRole($role);
            $this->entityManager->flush();
            $output->writeln(sprintf('Role "%s" removed from user with ID "%s".', $role, $userId));
        } else {
            $output->writeln('You must specify either --add or --remove option.');
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}
