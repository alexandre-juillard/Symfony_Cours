<?php

namespace App\Controller\Frontend;

use App\Entity\Article;
use App\Entity\Commentaire;
use App\Form\CommentaireType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/articles', 'app.articles')]
class ArticleController extends AbstractController
{
    #[Route('', '.index', methods: ['GET'])]
    public function index(ArticleRepository $articleRepo): Response
    {
        return $this->render('Frontend/Articles/index.html.twig', [
            'articles' => $articleRepo->findAllOrderByDate(),
        ]);
    }

    #[Route('/{slug}', '.show', methods: ['GET', 'POST'])]
    public function show(Article $article, Request $request, EntityManagerInterface $em): Response|RedirectResponse
    {
        if (!$article) {
            $this->addFlash('error', 'Article non trouvé');

            return $this->redirectToRoute('app.articles.index');
        }

        //faire controller du formulaire commentaire ici
        $commentaire = new Commentaire;

        $form = $this->createForm(CommentaireType::class, $commentaire);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();

            $commentaire
                ->setUser($user)
                ->setArticle($article)
                ->setEnable(true);

            $em->persist($commentaire);
            $em->flush();

            $this->addFlash('success', 'Commentaire déposé');

            return $this->redirectToRoute('app.articles.show', [
                'slug' => $article->getSlug()
            ]);
        }

        return $this->render('Frontend/Articles/show.html.twig', [
            'article' => $article,
            'form' => $form,
        ]);
    }
}
