<?php
// CRUD controller pour le dashboard//
namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Entity\Article;
use App\Entity\Categories;
use App\Entity\Commande;

    // page dashboard admin //
class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        return $this->render('admin/dashboard.html.twig');
    }
        // personnalisation du dashboard //
    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Music Art - Administration')
            ->renderContentMaximized();
    }
        // link des differents CRUD a afficher dans le dashboard admin //
    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        // yield MenuItem::linkToCrud('The Label', 'fas fa-list', EntityClass::class);
        yield MenuItem::linkToCrud('Utilisateur', 'fas fa-user', User::class);
        yield MenuItem::linkToCrud('Articles', 'fas fa-music', Article::class);
        yield MenuItem::linkToCrud('Categories', 'fa fa-tags', Categories::class);
        yield MenuItem::linkToUrl('Retour accueil Site', 'fas fa-home', $this->generateUrl('app_accueil'));
    }
}
