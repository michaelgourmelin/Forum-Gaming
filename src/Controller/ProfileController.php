<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Users;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;


#[Route('/profil', name: 'profile_')]

class ProfileController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index()
    {
        return $this->render('profile/index.html.twig');
    }


    #[Route('/comment', name: 'list')]
    public function userComments(Security $security,CommentRepository $commentRepository): Response
    {


        $user =  $security->getUser(); 

    
        return $this->render('profile/list.html.twig', [
            'comment' => $commentRepository->findBy(['users' => $user])
        ]);
    }





    #[Route('/modifercom/{slug}', name: 'modifier')]
    public function updateProduct(Request $request, EntityManagerInterface $entityManager, $id)
{
    $product = $entityManager->getRepository(Product::class)->find($id);

    // Check if the entity exists
    if (!$product) {
        throw $this->createNotFoundException('Product not found');
    }

    // Update the entity properties based on the request data
    $product->setName($request->request->get('name'));
    $product->setPrice($request->request->get('price'));
    // ... Update other properties as needed

    // Persist the changes to the database
    $entityManager->flush();

    // Optionally, redirect to another page or return a response
    return $this->redirectToRoute('product_show', ['id' => $product->getId()]);
}

}
