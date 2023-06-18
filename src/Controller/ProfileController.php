<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\CommentFormType;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;



#[Route('/profil', name: 'profile_')]

class ProfileController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index()
    {
        return $this->render('profile/index.html.twig');
    }


    #[Route('/comment', name: 'list')]
    public function userComments(Security $security, CommentRepository $commentRepository): Response
    {


        $user =  $security->getUser();


        return $this->render('profile/list.html.twig', [
            'comment' => $commentRepository->findBy(['users' => $user])
        ]);
    }





    #[Route('/modifercom/{id}', name: 'modifier')]
    public function updateComments(Request $request, EntityManagerInterface $em, Comment $comment)
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $form = $this->createForm(CommentFormType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em->persist($comment);
            $em->flush();

            return $this->redirectToRoute('profile_list');
        }
        return $this->render('comment/modifcom.html.twig', [

            'form' => $form->createView()

        ]);
    }
    #[Route('/supprimercom/{id}', name: 'supprimer')]

    public function deleteComments(Comment $comment, EntityManagerInterface $em)

    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $em->remove($comment);
        $em->flush();

        return $this->redirectToRoute('profile_list');
    }
}
