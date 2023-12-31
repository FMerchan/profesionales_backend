<?php

namespace App\Controller;

use App\Entity\Office;
use App\Repository\UserProfessionalRepository;
use App\Service\OfficeService;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/office")
 */
class OfficeController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private OfficeService $officeService;
    private UserProfessionalRepository $userProfessionalRepository;
    private $logger;


    public function __construct(
        EntityManagerInterface $entityManager,
        OfficeService $officeService,
        UserProfessionalRepository $userProfessionalRepository,
        LoggerInterface $logger
    ) {
        $this->entityManager = $entityManager;
        $this->officeService = $officeService;
        $this->userProfessionalRepository = $userProfessionalRepository;
        $this->logger = $logger;
    }

    /**
     * @Route("/create-office/{userProfessionalId}", name="create_office", methods={"POST"})
     */
    public function createOffice(Request $request, int $userProfessionalId): JsonResponse
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
            $office = $this->officeService->createOffice($userProfessional, $data);
            return new JsonResponse(['status' => true, 'message' => 'Office created']);
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
     * @Route("/edit-office/{userProfessionalId}/{officeId}", name="edit_office", methods={"POST"})
     */
    public function editOffice(Request $request, int $userProfessionalId, int $officeId): JsonResponse
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
            $office = $this->officeService->createOffice($userProfessional, $data, $officeId);
            return new JsonResponse(['status' => true, 'message' => 'Office created']);
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
     * @Route("/get-offices/{id}", name="get_offices", methods={"GET"})
     */
    public function getOffices(int $id): JsonResponse
    {
        $offices = $this->entityManager->getRepository(Office::class)->findBy(['userProfessional' => $id]);

        // Convert the professionals array to a format suitable for JSON response
        $responseArray = [];
        foreach ($offices as $office) {
            $responseArray[] = $office->getFullAsArray();
        }

        return $this->json($responseArray);
    }

    /**
     * @Route("/get-office/{id}", name="get_office", methods={"GET"})
     */
    public function getOffice(int $id): JsonResponse
    {
        $office = $this->entityManager->getRepository(Office::class)->find($id);

        // Convert the professionals array to a format suitable for JSON response
        $response = [];
        if ($office) {
            $response = $office->getFullAsArray();
        }

        return $this->json($response);
    }

    /**
     * @Route("/available-dates-times/{id}", name="available_dates_times", methods={"GET"})
     */
    public function availableDatesTimes(int $id): JsonResponse
    {
        $availableDatesAndTimes = $this->officeService->calculateAvailableDatesAndTimes($id);
        return $this->json($availableDatesAndTimes);
    }
}