<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\UserProfessional;
use App\Security\EmailAuthenticator;
use App\Service\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/user")
 */
class UserController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private $userService;
    private $logger;

    public function __construct(EntityManagerInterface $entityManager, UserService $userService, LoggerInterface $logger)
    {
        $this->entityManager = $entityManager;
        $this->userService = $userService;
        $this->logger = $logger;
    }

    /**
     * @Route("/authenticate-user", name="authenticate_user", methods={"POST"})
     */
    public function authenticateUser(Request $request, EmailAuthenticator $authenticator)
    {
        $email = $request->query->get('email');

        $entityManager = $this->entityManager;
        $userRepository = $entityManager->getRepository(User::class);

        $user = $userRepository->findOneBy(['email' => $email]);

        if (!$user) {
            // Handle invalid user
            // You can return a response indicating unsuccessful authentication
        }

        $authenticatedToken = $authenticator->createAuthenticatedToken($user, 'main');

        return $this->json([
            'message' => 'Authentication successful',
            'user' => $user,
        ]);
    }

    /**
     * @Route("/create-user", name="create_user", methods={"POST"})
     */
    public function createUser(Request $request): JsonResponse
    {
        try {
            $entityManager = $this->entityManager;
            $data = json_decode($request->getContent(), true);

            $userRepository = $entityManager->getRepository(User::class);
            $user = $userRepository->findOneBy(['email' => $data['data']['usuario']['perfil']['email']]);

            if (!$user) {
                $user = $this->userService->createUserMobile($data, null);
            }
            $userProfessional = $user->getUserProfessional();

            return new JsonResponse(['status' => true, 'message' => 'User created', 'id' => $userProfessional->getId()]);
        } catch (\Exception $e) {
            $errorMessage = 'Error creating user: ' . $e->getMessage() . "\n" . $e->getTraceAsString();
            $this->logger->error($errorMessage);

            return new JsonResponse(['status' => false, 'message' => 'Error creating user', 'error' => $e->getMessage()]);
        }
    }

    /**
     * @Route("/edit-user/{id}", name="edit_user", methods={"POST"})
     */
    public function editUser(Request $request, int $id): JsonResponse
    {
        try {
            $entityManager = $this->entityManager;
            $userProfessional = $entityManager->getRepository(UserProfessional::class)->find($id);

            if (!$userProfessional) {
                return new JsonResponse(['status' => false, 'message' => 'UserProfessional not found']);
            }

            $data = json_decode($request->getContent(), true);
            $this->userService->createUserMobile($data, $userProfessional);

            return new JsonResponse(['status' => true, 'message' => 'User created']);
        } catch (\Exception $e) {
            $errorMessage = 'Error creating user: ' . $e->getMessage();
            $this->logger->error($errorMessage);

            return new JsonResponse(['status' => false, 'message' => 'Error creating user', 'error' => $e->getMessage()]);
        }
    }

    /**
     * @Route("/check-user", name="check_user", methods={"GET"})
     */
    public function checkUser(Request $request): JsonResponse
    {
        $email = $request->query->get('email');

        $entityManager = $this->entityManager;
        $userRepository = $entityManager->getRepository(User::class);

        $user = $userRepository->findOneBy(['email' => $email]);

        if ($user) {
            return new JsonResponse(['exists' => true]);
        } else {
            return new JsonResponse(['exists' => false]);
        }
    }
}