<?php

namespace App\Service;

use App\Entity\Professional;
use App\Entity\User;
use App\Entity\UserProfessional;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserService
{
    private $entityManager;
    private $passwordHasher;

    public function __construct(EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher)
    {
        $this->entityManager = $entityManager;
        $this->passwordHasher = $passwordHasher;
    }

    public function createUserMobile(array $userData, ?UserProfessional $userProfessional): UserProfessional
    {
        $entityManager = $this->entityManager;
        $profile = $userData['data']['usuario']['perfil'];

        if (!$userProfessional) {
            $user = new User();
            $userProfessional = new UserProfessional();
        } else {
            $user = $userProfessional->getUser();
        }

        $user->setEmail($profile['email']);
        $user->addRole('ROLE_MOBILE');

        $userProfessional->setFirstName($profile['name']);
        $userProfessional->setLastName($profile['lastName']);
        $userProfessional->setLicenseNumber($profile['matricula']);
        //$userProfessional->setPhoneNumber($profile['phoneNumber']);
        $userProfessional->setAuthenticatorData($userData['data']['usuario']['authenticatorData']);
        $userProfessional->setType($profile['userType']);
        $userProfessional->setUser($user);

        $this->updateProfessions($userProfessional, $profile['professions']);

        // Generar una contraseña aleatoria
        $randomPassword = bin2hex(random_bytes(8));
        $user->setPassword($randomPassword);

        if (!$userProfessional->getId()) {
            $this->entityManager->persist($user);
            $this->entityManager->persist($userProfessional);
        }
        $this->entityManager->flush();

        return $userProfessional;
    }

    private function updateProfessions(UserProfessional $userProfessional, array $newProfessionIds): void
    {
        $entityManager = $this->entityManager;
        $professionalRepository = $entityManager->getRepository(Professional::class);

        $currentProfessions = $userProfessional->getUserProfessionalProfessionals();

        // Crear una colección de IDs de profesiones actuales
        $currentProfessionIds = [];
        foreach ($currentProfessions as $currentProfession) {
            $currentProfessionIds[] = $currentProfession->getProfessional()->getId();
        }

        // Agregar nuevas profesiones
        foreach ($newProfessionIds as $newProfessionId) {
            if (!in_array($newProfessionId, $currentProfessionIds)) {
                $professional = $professionalRepository->find($newProfessionId);
                if ($professional) {
                    $userProfessional->addProfessional($professional);
                }
            }
        }

        // Eliminar profesiones que ya no están en la lista nueva
        foreach ($currentProfessions as $currentProfession) {
            if (!in_array($currentProfession->getProfessional()->getId(), $newProfessionIds)) {
                $userProfessional->removeProfessional($currentProfession->getProfessional());
                $entityManager->remove($currentProfession);
            }
        }
    }
}