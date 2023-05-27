<?php

namespace App\Controller;


use App\Entity\Categories;
use App\Repository\ThemeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/categories', name: 'categories_')]

class CategoriesController extends AbstractController

{

    #[Route('/{slug}', name: 'list')]
    public function list(
        Categories $category,
        ThemeRepository $themeRepository,
        Request $request
    ): Response {
       
     
        $theme = $category->getThemes();
       
    

        return $this->render('categories/list.html.twig', compact('category', 'theme'));
        
       
    }
   
}
