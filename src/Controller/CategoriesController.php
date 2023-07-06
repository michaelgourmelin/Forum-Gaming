<?php

namespace App\Controller;


use App\Entity\Categories;
use App\Repository\ThemeRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/categories', name: 'categories_')]

class CategoriesController extends AbstractController

{

    #[Route('/{slug}', name: 'list')]

    /**
     * list theme by parent category
     *
     * @param Categories $category
     * @param ThemeRepository $themeRepository
     * @param Request $request
     * @return Response
     */
    public function list(
        Categories $category,
        ThemeRepository $themeRepository,
        Request $request,
        PaginatorInterface $paginatorInterface


    ): Response {


        $theme = $category->getThemes();

        $pagination = $paginatorInterface->paginate(

            $themeRepository->paginationQuery(),
            $request->query->get('page', 1),
            20
        );


        return $this->render('categories/list.html.twig', compact('category', 'theme', 'pagination'));
    }
}
