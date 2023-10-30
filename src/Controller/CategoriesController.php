<?php

namespace App\Controller;


use App\Entity\Categories;
use App\Repository\ThemeRepository;
use App\Service\VisitCounter;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/categories', name: 'categories_')]

class CategoriesController extends AbstractController

{

    #[Route('/{slug}/{id}', name: 'list')]

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
        PaginatorInterface $paginatorInterface,
        VisitCounter $visitCounter,

    ): Response {

        // Incrémentez le compteur de visites lorsque quelqu'un visite la page d'accueil
        // Obtenez le slug attendu pour cette catégorie

        $slug = $category->getSlug();
        $requestedSlug = $request->attributes->get('slug');
        if ($requestedSlug !== $slug) {
            return new RedirectResponse(
                $this->generateUrl('categories_list', ['slug' => $category->getSlug(), 'id' => $category->getId()]),
                RedirectResponse::HTTP_MOVED_PERMANENTLY
            );
        }

        // Obtenez le total des visites


        $theme = $category->getThemes();



        $pagination = $paginatorInterface->paginate(
            $themeRepository->paginationQuery($category->getSlug()),
            $request->query->getInt('page', 1),
            20
        );



        return $this->render('categories/list.html.twig', compact('category', 'pagination',));
    }
}
