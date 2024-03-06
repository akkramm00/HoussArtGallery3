<?php

namespace App\Controller\Admin;

use App\Entity\Colletion;
use App\Entity\Products;
use App\Entity\Contact;
use App\Entity\Review;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        return $this->render('admin/dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('HoussArtGallery3 - Administration')
            ->renderContentMaximized();
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToRoute('Accueil', 'fas fa-home', 'home.index');
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-dashboard');
        yield MenuItem::linkToCrud('Utilisateurs', 'fas fa-user', User::class);
        yield MenuItem::linkToCrud('Demandes de Contact', 'fas fa-envelope', Contact::class);
        yield MenuItem::linkToCrud('Listes des Ventes', 'fas fa-list', Products::class);
        yield MenuItem::linkToCrud('Les Colletions', 'fas fa-box-archive', Colletion::class);
        yield MenuItem::linkToCrud('Listes des Avis clients', 'fas fa-star', Review::class);
    }
}
