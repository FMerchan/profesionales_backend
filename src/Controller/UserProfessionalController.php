<?php

namespace App\Controller;

use App\Entity\UserProfessional;
use App\Entity\UserProfessionalProfessional;
use App\Repository\UserProfessionalRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/user-professional")
 */
class UserProfessionalController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private $logger;

    public function __construct(EntityManagerInterface $entityManager, LoggerInterface $logger)
    {
        $this->entityManager = $entityManager;
        $this->logger = $logger;
    }

    /**
     * @Route("/professionals/{professionalId}", name="get_professionals_by_profession", methods={"GET"})
     */
    public function getProfessionalsByProfession(int $professionalId): JsonResponse
    {
        /** @var UserProfessionalRepository $userProfessionalRepository */
        $userProfessionalRepository = $this->entityManager->getRepository(UserProfessional::class);
        $userProfessionals = $userProfessionalRepository->findByProfessionalId($professionalId);

        // Convert the professionals array to a format suitable for JSON response
        $responseArray = [];
        foreach ($userProfessionals as $userProfessional) {
            $userProfessionalData = $userProfessional->getUserProfessionalAsArray();
            if (count($userProfessionalData['offices']) > 0) {
                $responseArray[] = $userProfessionalData;
            }
        }

        return $this->json($responseArray);
    }
}