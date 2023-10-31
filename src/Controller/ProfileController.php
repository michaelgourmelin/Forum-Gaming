<?php

namespace App\Controller;


use Symfony\Component\Mime\Address;
use App\Entity\Comment;
use App\Entity\UserPicture;
use App\Entity\Users;
use App\Form\CommentFormType;
use App\Form\UsersPicturesType;
use App\Repository\CommentRepository;
use App\Security\EmailVerifier;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
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


        /**
         * @var Users
         */
        $user = $this->getUser();

        // Vérifiez si l'utilisateur est banni
        if ($user->getIsBanned()) {
            // Redirigez l'utilisateur vers une page d'erreur ou affichez un message d'interdiction
            return $this->render('/bundles/TwigBundle/Exception/error_banned.html.twig'); // Vous devez créer ce template
        }

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
        /**
         * @var Users
         */
        $user = $this->getUser();

        // Vérifiez si l'utilisateur est banni
        if ($user->getIsBanned()) {
            // Redirigez l'utilisateur vers une page d'erreur ou affichez un message d'interdiction
            return $this->render('/bundles/TwigBundle/Exception/error_banned.html.twig'); // Vous devez créer ce template
        }

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
        /**
         * @var Users
         */
        $user = $this->getUser();

        // Vérifiez si l'utilisateur est banni
        if ($user->getIsBanned()) {
            // Redirigez l'utilisateur vers une page d'erreur ou affichez un message d'interdiction
            return $this->render('/bundles/TwigBundle/Exception/error_banned.html.twig'); // Vous devez créer ce template
        }

        $em->remove($comment);
        $em->flush();

        return $this->redirectToRoute('profile_list');
    }

    #[Route('/send-email', name: 'send_email')]

    public function sendEmail()
    {
        // Récupérer l'utilisateur authentifié
        /**
         * @var Users
         */
        $user = $this->getUser();
        // Vérifiez si l'utilisateur est banni
        if ($user->getIsBanned()) {
            // Redirigez l'utilisateur vers une page d'erreur ou affichez un message d'interdiction
            return $this->render('/bundles/TwigBundle/Exception/error_banned.html.twig'); // Vous devez créer ce template
        }

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



    #[Route('/picture', name: 'picture')]

    public function addPictures(Request $request, EntityManagerInterface $em)
    {
        /**
         * @var Users
         */
        $user = $this->getUser();
    
        if ($user->getIsBanned()) {
            return $this->render('error_banned.html.twig');
        }
    
        $userPicture = new UserPicture();
        $form = $this->createForm(UsersPicturesType::class, $userPicture);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            // Use VichUploader to handle file upload
            $imageFile = $userPicture->getImageFile();
    
            if ($imageFile) {
                $userPicture->setImageFile($imageFile); // This is usually not needed if using VichUploader
                $userPicture->setImageName($imageFile->getFileName()); // Set the file name from the uploaded file
                $userPicture->setImageSize($imageFile->getSize()); // Set the file size from the uploaded file
    
                $userPicture->setUser($user);
                $em->persist($userPicture);
                $em->flush();
            }
            return $this->redirectToRoute('profile_index');
        }
    
        return $this->render('profile/add_picture.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    #[Route('/remove/{id}', name: 'remove')]

    public function deletePicture( EntityManagerInterface $em): RedirectResponse
    {
        // Récupérer l utilisateur authentifié
        /**
         * @var Users
         */
        $user = $this->getUser();

        // Vérifiez if the user has a profile picture
        if ($user->getUserPicture() !== null) {
            $picture = $user->getUserPicture();
            $user->setUserPicture(null); // Remove the user's profile picture

            $em->remove($picture); // Mark the profile picture for removal
            $em->flush(); // Persist the changes

            // Redirect to the user's profile or another appropriate page
            return $this->redirectToRoute('profile_index');
        }

        // Redirect to the user's profile or another appropriate page
        return $this->redirectToRoute('profile_index');
    }
}
