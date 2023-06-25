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

    /**
     * list comment by parent theme
     *
     * @param Theme $theme
     * @param CommentRepository $commentRepository
     * @param Request $request
     * @return Response
     */
    public function list(

        Theme $theme,
        CommentRepository $commentRepository,
        Request $request
    ): Response {


        $comment = $theme->getComments();

        return $this->render('comment/list.html.twig', compact('theme', 'comment',));
    }

    #[Route('/ajoutcom/{slug}', name: 'commentaire')]

    /**
     * add a comment
     *
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param Theme $theme
     * @param SluggerInterface $slugger
     * @return Response
     */
    public function addcom(
        Request $request,
        EntityManagerInterface $em,
        Theme $theme,
        SluggerInterface $slugger
    ): Response {

        $this->denyAccessUnlessGranted('ROLE_USER');


        $comment = new Comment();
        $comment->setUsers($this->getUser());
        $comment->setTheme($theme);
        $form = $this->createForm(CommentFormType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $slug = $slugger->slug($theme->getName());
            $theme->setSlug($slug);
            $em->persist($comment);
            $em->flush();
            // $this->addFlash('success', 'Commentaire ajouté');

            return $this->redirectToRoute('index_');
        }
        return $this->render('comment/addcom.html.twig', [

            'form' => $form->createView()

        ]);
    }
}
