<?php

namespace App\Controller;

use App\Entity\Turn;
use App\Entity\UserProfessional;
use App\Repository\TurnRepository;
use App\Repository\UserProfessionalRepository;
use App\Service\TurnService;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/turn")
 */
class TurnController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    private TurnService $turnService;

    private UserProfessionalRepository $userProfessionalRepository;

    private TurnRepository $turnRepository;

    private $logger;

    public function __construct(
        EntityManagerInterface $entityManager,
        TurnService $turnService,
        UserProfessionalRepository $userProfessionalRepository,
        TurnRepository $turnRepository,
        LoggerInterface $logger
    )
    {
        $this->entityManager = $entityManager;
        $this->turnService = $turnService;
        $this->userProfessionalRepository = $userProfessionalRepository;
        $this->turnRepository = $turnRepository;
        $this->logger = $logger;
    }

    /**
     * @Route("/turns-open/{userProfessionalId}", name="get_open_turn_by_user_professional", methods={"GET"})
     */
    public function getOpenTurnsByProfessional(int $userProfessionalId): JsonResponse
    {
        $turns = $this->entityManager->getRepository(Turn::class)
            ->findOpenTurns($userProfessionalId);

        // Convert the professionals array to a format suitable for JSON response
        $responseArray = [];
        foreach ($turns as $turn) {
            $responseArray[] = $turn->getAsArray();
        }

        return $this->json($responseArray);
    }

    /**
     * @Route("/turns-close/{userProfessionalId}", name="get_close_turn_by_user_professional", methods={"GET"})
     */
    public function getCloseTurnsByProfessional(int $userProfessionalId): JsonResponse
    {
        $turns = $this->entityManager->getRepository(Turn::class)
            ->findCloseTurns($userProfessionalId);

        // Convert the professionals array to a format suitable for JSON response
        $responseArray = [];
        foreach ($turns as $turn) {
            $responseArray[] = $turn->getAsArray();
        }

        return $this->json($responseArray);
    }

    /**
     * @Route("/professional/{userProfessionalId}", name="get_professional_turn", methods={"GET"})
     */
    public function getProfessionalTurns(int $userProfessionalId): JsonResponse
    {
        $turns = $this->entityManager->getRepository(Turn::class)
            ->findBy(['userProfessional' => $userProfessionalId]);

        // Convert the professionals array to a format suitable for JSON response
        $responseArray = [];
        foreach ($turns as $turn) {
            $responseArray[] = $turn->getAsArray();
        }

        return $this->json($responseArray);
    }

    /**
     * @Route("/create/{userProfessionalId}", name="create_turn", methods={"POST"})
     */
    public function createTurn(Request $request, int $userProfessionalId): JsonResponse
    {
        try {
            // Obtener el UserProfessional desde la base de datos
            $userProfessional = $this->userProfessionalRepository->find($userProfessionalId);

            if (!$userProfessional) {
                return new JsonResponse(['status' => false, 'message' => 'UserProfessional not found']);
            }

            $jsonData = $request->getContent();
            $data = json_decode($jsonData, true);

            // Crear la oficina utilizando el servicio
            $turn = $this->turnService->createTurn($userProfessional, $data);
            return new JsonResponse(['status' => true, 'message' => 'Turn created']);
        } catch (\InvalidArgumentException $e) {
            $errorMessage = 'Error creating office: ' . $e->getMessage();
            $this->logger->error($errorMessage);

            return new JsonResponse(['status' => false, 'message' => $e->getMessage()]);
        } catch (\Exception $e) {
            $errorMessage = 'Error creating office: ' . $e->getMessage() . "\n" . $e->getTraceAsString();
            $this->logger->error($errorMessage);

            return new JsonResponse(['status' => false, 'error' => $e->getMessage()]);
        }
    }

    /**
     * @Route("/user-cancel/{turnId}", name="user_cancel_turn", methods={"GET"})
     */
    public function userCancelTurn(Request $request, int $turnId): JsonResponse
    {
        // Obtener el UserProfessional desde la base de datos
        $turn = $this->turnRepository->find($turnId);

        if (!$turn) {
            return new JsonResponse(['status' => false, 'message' => 'Turn not exist']);
        }

        $turn->setCancelled(true);
        $turn->setCancelledBy(Turn::CANCELED_BY_USER);
        $this->entityManager->flush();

        return new JsonResponse(['status' => true, 'message' => 'Turn Cancelled']);
    }

    /**
     * @Route("/professional-cancel/{turnId}", name="professional_cancel_turn", methods={"GET"})
     */
    public function professionalCancelTurn(Request $request, int $turnId): JsonResponse
    {
        // Obtener el UserProfessional desde la base de datos
        $turn = $this->turnRepository->find($turnId);

        if (!$turn) {
            return new JsonResponse(['status' => false, 'message' => 'Turn not exist']);
        }

        $turn->setCancelled(true);
        $turn->setCancelledBy(Turn::CANCELED_BY_PROFESSIONAL);
        $this->entityManager->flush();

        return new JsonResponse(['status' => true, 'message' => 'Turn Cancelled']);
    }
}