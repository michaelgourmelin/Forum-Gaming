<?php

namespace App\Controller;

use App\Entity\Theme;
use App\Entity\Users;
use App\Form\ThemeFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;


class ThemeController extends AbstractController


{
    #[Route('/', name: 'index_')]
    public function index()

    {
        return $this->render('main/index.html.twig');
    }

    #[Route('/ajout', name: 'ajouter')]

    /**
     * add a theme
     *
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param SluggerInterface $slugger
     * @return Response
     */
    public function add(
        Request $request,
        EntityManagerInterface $em,
        SluggerInterface $slugger
    ): Response {

        $this->denyAccessUnlessGranted('ROLE_USER');

        /**
         * @var Users
         */
        $user = $this->getUser();

        // Vérifiez si l'utilisateur est banni
        if ($user->getIsBanned()) {
            // Redirigez l'utilisateur vers une page d'erreur ou affichez un message d'interdiction
            return $this->render('/bundles/TwigBundle/Exception/error_banned.html.twig'); // Vous devez créer ce template
        }

        $theme = new Theme();
        $theme->setUsers($this->getUser());
        $form = $this->createForm(ThemeFormType::class, $theme);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $category = $theme->getCategories();
            $slug = $slugger->slug($theme->getName());
            $theme->setSlug($slug);
            $em->persist($theme);
            $em->flush();
            // $this->addFlash('success' , 'Theme ajouté');


            return $this->redirectToRoute('categories_list', [
                'slug' => $category->getSlug(), 'id' => $category->getId(),
            ]);
        }

        return $this->render('theme/add.html.twig', [

            'form' => $form->createView()

        ]);
    }
}
