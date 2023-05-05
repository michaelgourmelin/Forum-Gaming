<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Theme;
use App\Form\CommentFormType;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/comment', name: 'comment_')]

class CommentController extends AbstractController

{
    #[Route('/{slug}', name: 'list')]
    public function list(
        Theme $theme,
        CommentRepository $commentRepository,
        Request $request
    ): Response {


        $comment = $theme->getComments();

        return $this->render('comment/list.html.twig', compact('theme', 'comment'));
    }

    #[Route('/ajoutcom', name: 'commentaire')]

    public function addcom(
        Request $request,
        EntityManagerInterface $em,
        SluggerInterface $slugger
    ): Response {

        $this->denyAccessUnlessGranted('ROLE_USER');

        $theme = new Theme();
        $slug = $slugger->slug($theme->getName());
        $theme->setSlug($slug);
        $comment = new Comment();
        $comment->setUsers($this->getUser());
        $form = $this->createForm(CommentFormType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

          
            $em->persist($comment);
            $em->flush();
            $this->addFlash('success', 'Commentaire ajouté');

            return $this->redirectToRoute('index_');
        }
        return $this->render('comment/addcom.html.twig',[

            'form' =>$form->createView()
    
        ]);
    }
}
