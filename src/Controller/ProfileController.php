<?php

namespace App\Controller;


use Symfony\Component\Mime\Address;
use App\Entity\Comment;
use App\Entity\Users;
use App\Form\CommentFormType;
use App\Repository\CommentRepository;
use App\Security\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
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

    private EmailVerifier $emailVerifier;
    public function __construct(EmailVerifier $emailVerifier)
    {
        $this->emailVerifier = $emailVerifier;
    }

    #[Route(name: 'index')]
    public function index()
    {
        /**
         * @var Users
         */
        $user = $this->getUser();

        // Vérifiez si l'utilisateur est banni
        if ($user->getIsBanned()) {
            // Redirigez l'utilisateur vers une page d'erreur ou affichez un message d'interdiction
            return $this->render('/bundles/TwigBundle/Exception/error_banned.html.twig'); // Vous devez créer ce template
        }

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


    #[Route('/update/{id}', name: 'update')]

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
    #[Route('/delete/{id}', name: 'delete')]

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

    #[Route('/send-email', name: 'send_email')]

    public function sendEmail()
    {
        // Récupérer l'utilisateur authentifié
        $user = $this->getUser();

        // Vérifier si l'utilisateur existe
        if ($user instanceof Users) {
            $this->emailVerifier->sendEmailConfirmation(
                'app_verify_email',
                $user,
                (new TemplatedEmail())
                    ->from(new Address('no-reply@eslforum.net', 'Mail bot'))
                    ->to($user->getEmail())
                    ->subject('Please Confirm your Email')
                    ->htmlTemplate('registration/confirmation_email.html.twig')
            );
            $this->addFlash('success', 'Un email a était envoyé , veuillez vérifier votre boîte email');
            return $this->redirectToRoute('profile_index');
        }
    }
}
