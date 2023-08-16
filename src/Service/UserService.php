<?php

namespace App\Service;

use App\Entity\User;
use App\Entity\UserProfessional;
use Doctrine\ORM\EntityManagerInterface;

class UserService
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function createUser(array $userData): void
    {
        $user = new User();
        $user->setEmail($userData['email']);
        $user->addRole('ROLE_MOBILE');

        $userProfessional = new UserProfessional();
        $userProfessional->setFirstName($userData['firstName']);
        $userProfessional->setLastName($userData['lastName']);
        $userProfessional->setLicenseNumber($userData['licenseNumber']);
        $userProfessional->setPhoneNumber($userData['phoneNumber']);
        $userProfessional->setUser($user);

        // Encode and set the password
        $this->entityManager->persist($user);

        $this->entityManager->persist($userProfessional);
        $this->entityManager->flush();
    }
}