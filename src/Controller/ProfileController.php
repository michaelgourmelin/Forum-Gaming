<?php

namespace App\Controller;

use App\Entity\Users;
use App\Repository\UsersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/profil', name: 'profile_')]

class ProfileController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index()
    {
        return $this->render('profile/index.html.twig');
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
