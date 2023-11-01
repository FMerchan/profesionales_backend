<?php

namespace App\Controller;

use App\Entity\LegalDocument;
use App\Entity\User;
use App\Security\EmailAuthenticator;
use App\Service\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/legal")
 */
class LegalDocumentController extends AbstractController
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
     * @Route("/tyc", name="tyc", methods={"GET"})
     */
    public function tycPage()
    {
        $repository = $this->entityManager->getRepository(LegalDocument::class);
        // Suponiendo que '1' corresponde al tipo de Términos y Condiciones
        $activeTermsAndConditions = $repository->findOneBy([
            'type' => LegalDocument::TERMS_AND_CONDITIONS,
            'isActive' => true,
        ]);


        return $this->render('legalDocument/terms_and_conditions.html.twig', [
            'termsAndConditions' => $activeTermsAndConditions->getContent(),
        ]);
    }

    /**
     * @Route("/privacy-policy", name="privacy_policy", methods={"GET"})
     */
    public function privacyPolicyPage()
    {
        $repository = $this->entityManager->getRepository(LegalDocument::class);
        // Suponiendo que '1' corresponde al tipo de Términos y Condiciones
        $activeTermsAndConditions = $repository->findOneBy([
            'type' => LegalDocument::PRIVACY_POLICY,
            'isActive' => true,
        ]);


        return $this->render('legalDocument/privacy_policy.html.twig', [
            'privacyPolicy' => $activeTermsAndConditions->getContent(),
        ]);
    }
}