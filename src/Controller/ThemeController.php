<?php

namespace App\Controller;

use App\Entity\Theme;
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
   public function add(Request $request, EntityManagerInterface $em,
   SluggerInterface $slugger):Response
   {
    
    $this->denyAccessUnlessGranted('ROLE_USER');

    $theme = new Theme();
    $theme->setUsers($this->getUser());
    $form =$this->createForm(ThemeFormType::class, $theme);

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      
        $slug = $slugger->slug($theme->getName());
        $theme->setSlug($slug);
        $em->persist($theme);
        $em->flush();
        $this->addFlash('success' , 'Theme ajouté');

        return $this->redirectToRoute('index_');
      

    }

    return $this->render('theme/add.html.twig',[

        'form' =>$form->createView()

    ]);
   }
 
} 
