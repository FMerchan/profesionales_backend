<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\UserProfessional;
use App\Security\EmailAuthenticator;
use App\Service\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\RememberMeBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;

/**
 * @Route("/user")
 */
class UserController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private $userService;

    public function __construct(EntityManagerInterface $entityManager, UserService $userService)
    {
        $this->entityManager = $entityManager;
        $this->userService = $userService;
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
    public function createUser(Request $request, UserPasswordEncoderInterface $passwordEncoder): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $this->userService->createUser($data);

        return new JsonResponse(['message' => 'User created']);
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