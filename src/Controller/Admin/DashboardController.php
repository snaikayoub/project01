<?php

namespace App\Controller\Admin;

use App\Entity\Carousel;
use App\Entity\Group;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'app_admin')]
    public function index(): Response
    {
        //return parent::index();

        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
        // $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        // return $this->redirect($adminUrlGenerator->setController(OneOfYourCrudController::class)->generateUrl());

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirect('...');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //
        return $this->render('admin/dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Project01');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa-solid fa-wand-magic-sparkles fa-xl');
        yield MenuItem::linkToCrud('Utilisateurs', 'fas fa-user fa-xl', User::class)->setPermission('ROLE_ADMIN');
        yield MenuItem::linkToCrud('Groupes de Gestions', 'fas fa-newspaper fa-xl', Group::class);
        yield MenuItem::linkToCrud('Gestion de Carousels', 'fas fa-image fa-xl', Carousel::class);
        yield MenuItem::linkToRoute('Retour Page d\'Accueil','fa fa-home fa-xl','app_home');

    }
}
