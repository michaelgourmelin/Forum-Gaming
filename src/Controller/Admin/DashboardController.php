<?php

namespace App\Controller\Admin;

use App\Entity\Categories;
use App\Entity\Comment;
use App\Entity\Theme;
use App\Entity\Users;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {


        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        return $this->redirect($adminUrlGenerator->setController(CategoriesCrudController::class)->generateUrl());
    }


    public function configureMenuItems(): iterable
    {

        yield MenuItem::linkToCrud('Categories', 'fa fa-categories',Categories::class);

    
        // yield MenuItem::linktoRoute('Back to the website', 'fas fa-home', 'homepage');
        //    yield MenuItem::linkToCrud('Categories', 'fa fa-tags', Categories::class);
        yield MenuItem::linkToCrud('Theme', 'fa fa-theme', Theme::class);
        yield MenuItem::linkToCrud('Comment', 'fa fa-comment', Comment::class);
        yield MenuItem::linkToCrud('Users', 'fa fa-users', Users::class);
       
    }
    

    
}
