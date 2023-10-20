<?php

namespace App\Controller\Admin;

use App\Entity\LegalDocument;
use App\Entity\Office;
use App\Entity\Professional;
use App\Entity\UserProfessional;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        return $this->render('bundles/EasyAdminBundle/welcome.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Profesionales Backend');
    }

    public function configureMenuItems(): iterable
    {
        return [
            MenuItem::linkToDashboard('Dashboard', 'fa fa-home'),

            MenuItem::linkToCrud('Usuarios', 'fa fa-user', User::class),
            MenuItem::linkToCrud('Profesionales', 'fa fa-stethoscope', UserProfessional::class),
            MenuItem::linkToCrud('Profesiones', 'fa fa-book', Professional::class),
            MenuItem::linkToCrud('Oficinas', 'fa fa-building', Office::class),
            MenuItem::linkToRoute('App', 'fas fa-mobile-alt', 'app_page'),
            MenuItem::linkToCrud('TyC/Doc Privacidad', 'fas fa-mobile-alt', LegalDocument::class),
        ];
    }
}
