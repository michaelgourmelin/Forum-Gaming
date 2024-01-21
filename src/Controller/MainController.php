<?php

namespace App\Controller;

use App\Entity\Categories;
use App\Repository\CategoriesRepository;
use App\Repository\ThemeRepository;
use App\Service\ApiEsport;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{


    private $esportApiKey;

    public function __construct(ApiEsport $esportApiKey)
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
            'categories' => $categoriesRepository->findBy([], ['category_order' => 'asc'])
        ]);
    }

    #[Route('/search', name: 'search')]
    public function search(Request $request,
    ThemeRepository $themeRepository,)

    {
        $search = $request->query->get('search');

        $theme = $themeRepository->findOneByName($search);

        return $this->render('categories/result.html.twig',[

            'theme' => $theme,
            'search' => $search
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

    #[Route('/valorant/tournament', name: 'tournament')]

    public function tournament(): Response
    {

        $jsonResponse = $this->esportApiKey->fetchValorantTournament();
        $jsonResponseMatch = $this->esportApiKey->fetchValorantMatch();

        $tournament = json_decode($jsonResponse->getContent(), true);
        $matches = json_decode($jsonResponseMatch->getContent(), true);

        // dd($tournament);
        return $this->render('main/valorant_tournament.html.twig', [
            'tournament' => $tournament,
            'matches' => $matches
        ]);
    }

    #[Route('/valorant/matches', name: 'matches')]

    public function match(): Response
    {

        $jsonResponse = $this->esportApiKey->fetchValorantMatch();
    
       
        $matches = json_decode($jsonResponse->getContent(), true);

       
        return $this->render('main/valorant_matches.html.twig', [
            'matches' => $matches,
         

        ]);
    }
}
