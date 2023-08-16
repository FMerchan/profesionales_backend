<?php

namespace App\Security;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\RememberMeBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class EmailAuthenticator extends AbstractGuardAuthenticator
{
    use TargetPathTrait;

    private EntityManagerInterface $entityManager;
    private $userRepository;
    private $urlGenerator;

    public function __construct(EntityManagerInterface $entityManager, UserRepository $userRepository, UrlGeneratorInterface $urlGenerator)
    {
        $this->entityManager = $entityManager;

        $this->userRepository = $userRepository;
        $this->urlGenerator = $urlGenerator;
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

    public function supports(Request $request)
    {
        return $request->attributes->get('_route') === 'app_login' && $request->isMethod('POST');
    }

    public function getCredentials(Request $request)
    {
        return [
            'email' => $request->request->get('email'),
        ];
    }

    public function getUser($credentials, $userProvider)
    {
        $email = $credentials['email'];

        return $this->userRepository->findOneByEmail($email);
    }

    public function checkCredentials($credentials, $user)
    {
        return $user !== null;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        $targetPath = $this->getTargetPath($request->getSession(), $providerKey);

        if (!$targetPath) {
            $targetPath = $this->urlGenerator->generate('app_homepage');
        }

        return new RedirectResponse($targetPath);
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        $request->getSession()->set(Security::AUTHENTICATION_ERROR, $exception);

        $url = $this->urlGenerator->generate('app_login');

        return new RedirectResponse($url);
    }

    protected function getLoginUrl()
    {
        return $this->urlGenerator->generate('app_login');
    }

    public function supportsRememberMe()
    {
        return false; // Change this according to your needs
    }
    public function start(Request $request, AuthenticationException $authException = null): Response
    {
        $url = $this->getLoginUrl($request);

        return new RedirectResponse($url);
    }

    public function authenticate(Request $request): Passport
    {
        $email = $request->request->get('email', '');

        $request->getSession()->set(Security::LAST_USERNAME, $email);

        // Aquí puedes agregar la lógica para verificar si el usuario existe con el correo electrónico
        // Puedes usar tu propio servicio para hacer la verificación

        return new Passport(
            new UserBadge($email),
            new PasswordCredentials(''),
            [
                new CsrfTokenBadge('authenticate', $request->request->get('_csrf_token')),
                new RememberMeBadge(),
            ]
        );
    }
}
