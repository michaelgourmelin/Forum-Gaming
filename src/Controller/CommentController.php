<?php

namespace App\Controller;


use App\Entity\Comment;
use App\Entity\Theme;
use App\Form\CommentFormType;
use App\Repository\CommentRepository;
use App\Service\VisitCounter;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/comment', name: 'comment_')]

class CommentController extends AbstractController

{
    #[Route('/{slug}/{id}', name: 'list')]

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
        Request $request,
        PaginatorInterface $paginatorInterface,
        VisitCounter $visitCounter,

    ): Response {

        $slug = $theme->getSlug();
        $requestedSlug = $request->attributes->get('slug');
        if ($requestedSlug !== $slug) {
            return new RedirectResponse(
                $this->generateUrl('comment_list', ['slug' => $theme->getSlug(), 'id' => $theme->getId()]),
                RedirectResponse::HTTP_MOVED_PERMANENTLY
            );
        }


        $visitCounter->increment();
        $category = $theme->getCategories();
        $comment = $theme->getComments();
        $pagination = $paginatorInterface->paginate(
            $commentRepository->paginationQuery($theme->getSlug()),
            $request->query->getInt('page', 1),
            25
        );

        return $this->render('comment/list.html.twig', compact('category', 'theme', 'pagination'));
    }

    #[Route('/addcom/{slug}/{id}', name: 'commentaire')]

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

            return $this->redirectToRoute('comment_list', [
                'slug' => $theme->getSlug(),
                'id' => $theme->getId()
            ]);
        }
        return $this->render('comment/addcom.html.twig', [

            'form' => $form->createView()

        ]);
    }
}
