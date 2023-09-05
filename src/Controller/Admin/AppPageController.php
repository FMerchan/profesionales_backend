<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AppPageController extends AbstractController
{
    #[Route('/app/page', name: 'app_page')]
    public function index(): Response
    {
        return $this->render('app_page/index.html.twig', [
            'controller_name' => 'AppPageController',
        ]);
    }
}
