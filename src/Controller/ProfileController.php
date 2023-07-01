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

/**
 * index profil
 */
class ProfileController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index()
    {
        return $this->render('profile/index.html.twig');
    }


    #[Route('/comment', name: 'list')]

    /**
     * display logged in user's comments
     *
     * @param Security $security
     * @param CommentRepository $commentRepository
     * @return Response
     */
    public function userComments(Security $security, CommentRepository $commentRepository): Response
    {


        $user =  $security->getUser();
        

        return $this->render('profile/list.html.twig', [
            'comment' => $commentRepository->findBy(['users' => $user])
        ]);
    }


    #[Route('/modifercom/{id}', name: 'modifier')]

    /**
     * edit logged in user comments
     *
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param Comment $comment
     * @return void
     */
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

    /**
     * dalete logged in user comments
     *
     * @param Comment $comment
     * @param EntityManagerInterface $em
     * @return void
     */
    public function deleteComments(Comment $comment, EntityManagerInterface $em)

    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $em->remove($comment);
        $em->flush();

        return $this->redirectToRoute('profile_list');
    }
}
