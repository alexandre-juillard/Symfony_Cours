<?php

namespace App\Controller\Backend;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin/articles', 'admin.articles')]
class ArticleController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em,
        private ArticleRepository $articleRepo,
    ) {
    }

    #[Route('', '.index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('Backend/articles/index.html.twig', [
            'articles' => $this->articleRepo->findAllOrderByDate(true),
        ]);
    }

    #[Route('/create', '.create', methods: ['GET', 'POST'])]
    public function create(Request $request): Response|RedirectResponse
    {
        $article = new Article;

        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //on recupere le user connecté
            $user = $this->getUser();

            //on définit l'auteur de l'article avec le setter
            $article->setUser($user);
            $this->em->persist($article);
            $this->em->flush();

            $this->addFlash('success', 'L\'article a été créé');

            return $this->redirectToRoute('admin.articles.index');
        }

        return $this->render('Backend/Articles/create.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{slug}/edit', '.edit', methods: ['GET', 'POST'])]
    public function edit(?Article $article, Request $request): Response|RedirectResponse
    {
        //slug dans URL se trouve grace a l'objet en parametre dans la methode (Article)
        if (!$article) {
            $this->addFlash('error', 'Article introuvable');

            return $this->redirectToRoute('admin.articles.index');
        }

        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->em->persist($article);
            $this->em->flush();

            $this->addFlash('success', 'Article modifié avec succès');

            return $this->redirectToRoute('admin.articles.index');
        }

        return $this->render('Backend/Articles/edit.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', '.delete', methods: ['POST'])]
    public function delete(?Article $article, Request $request): RedirectResponse
    {
        if (!$article) {
            $this->addFlash('error', 'Article non trouvé');

            return $this->redirectToRoute('admin.articles.index');
        }

        if ($this->isCsrfTokenValid('delete' . $article->getId(), $request->request->get('token'))) {
            $this->em->remove($article);
            $this->em->flush();

            $this->addFlash('success', 'Article supprimé avec succès');
        } else {
            $this->addFlash('error', 'TokenCSRF invalide');
        }

        return $this->redirectToRoute('admin.articles.index');
    }

    #[Route('/{id}/switch', '.switch', methods: ['GET'])]
    public function switch(?Article $article): JsonResponse
    {
        if (!$article) {
            return new JsonResponse([
                'status' => 'Error',
                'message' => 'Article non trouvé'
            ], Response::HTTP_NOT_FOUND);
        }

        $article->setEnable(!$article->isEnable());
        $this->em->persist($article);
        $this->em->flush();

        return new JsonResponse([
            'status' => 'success',
            'message' => 'Visibility changed',
            'enable' => $article->isEnable(),
        ], Response::HTTP_CREATED);
    }
}
