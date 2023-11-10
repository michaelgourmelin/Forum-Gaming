<?php

namespace App\Controller;


use App\Entity\Categories;
use App\Repository\ThemeRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


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

    ): Response {

 

        $slug = $category->getSlug();
        $requestedSlug = $request->attributes->get('slug');
        if ($requestedSlug !== $slug) {
            return new RedirectResponse(
                $this->generateUrl('categories_list', ['slug' => $category->getSlug(), 'id' => $category->getId()]),
                RedirectResponse::HTTP_MOVED_PERMANENTLY
            );
        }

        $pagination = $paginatorInterface->paginate(
            $themeRepository->paginationQuery($category->getSlug()),
            $request->query->getInt('page', 1),
            20
        );



        return $this->render('categories/list.html.twig', compact('category', 'pagination',));
    }
}
