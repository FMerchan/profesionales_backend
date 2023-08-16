<?php

namespace App\Controller;

use App\Entity\Professional;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ProfessionalController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/professionals", name="get_professionals", methods={"GET"})
     */
    public function getProfessionals(): JsonResponse
    {
        $professionals = $this->entityManager->getRepository(Professional::class)->findAll();

        // Convert the professionals array to a format suitable for JSON response
        $responseArray = [];
        foreach ($professionals as $professional) {
            $responseArray[] = [
                'id' => $professional->getId(),
                'name' => $professional->getName(),
                'description' => $professional->getDescription(),
            ];
        }

        return $this->json($responseArray);
    }
}