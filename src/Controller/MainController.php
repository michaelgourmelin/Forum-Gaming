<?php

namespace App\Controller;

use App\Repository\CategoriesRepository;
use App\Service\ApiEsport;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{


    private $esportApiKey;

    public function __construct( ApiEsport $esportApiKey)
    {
       $this->esportApiKey = $esportApiKey;
    }

    #[Route('/', name: 'main')]
    /**
     * list all category by parent-category
     *
     * @param CategoriesRepository $categoriesRepository
     * @return Response
     */
    public function index(CategoriesRepository $categoriesRepository): Response
    {
        return $this->render('main/index.html.twig', [
            'categories' => $categoriesRepository->findBy([],['category_order' => 'asc'])
        ]);

    }
    #[Route('/game', name: 'game')]

    public function game(Request $request): Response
    {
        $search = $request->query->get('search');
      
    
        $jsonResponse = $this->esportApiKey->fetchgame($search);
    
        $game = json_decode($jsonResponse->getContent(), true);
    
        return $this->render('main/games.html.twig', [
            'game' => $game,
            'search' => $search
        ]);
    }

}

