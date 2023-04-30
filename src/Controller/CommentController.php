<?php

namespace App\Controller;

use App\Entity\Theme;
use App\Repository\CommentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
}

