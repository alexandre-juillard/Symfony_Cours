<?php

namespace App\Controller\Backend;

use App\Entity\Article;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;

#[Route('admin/commentaires', 'admin.commentaires')]
class CommentaireController extends AbstractController
{
    #[Route('/{slug}', '.index', methods: ['GET'])]
    public function index(?Article $article): Response|RedirectResponse
    {
        if (!$article) {
            $this->addFlash('error', 'Article non trouvé');

            return $this->redirectToRoute('admin.articles.index');
        }

        return $this->render('Backend/Commentaires/index.html.twig', [
            'commentaires' => $article->getCommentaires(),
        ]);
    }

    //methode delete
}
