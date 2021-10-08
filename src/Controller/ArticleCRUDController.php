<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/article/crud")
 */
class ArticleCRUDController extends AbstractController {
    /**
     * @Route("/", name="article_crud_index", methods={"GET"})
     */
    public function index(ArticleRepository $articleRepository): Response {
        return $this->render('article_crud/index.html.twig', [
            'articles' => $articleRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="article_crud_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response {
        $article = new Article();
        $article->setDatePublication(new DateTime);
        
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($article);
            $entityManager->flush();

            return $this->redirectToRoute('article_crud_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('article_crud/new.html.twig', [
            'article' => $article,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="article_crud_show", methods={"GET"})
     */
    public function show(Article $article): Response {
        return $this->render('article_crud/show.html.twig', [
            'article' => $article,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="article_crud_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Article $article): Response {
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('article_crud_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('article_crud/edit.html.twig', [
            'article' => $article,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}/delete", name="article_crud_delete", methods={"GET"})
     */
    public function delete(Request $request, Article $article): Response {
        if ($this->isCsrfTokenValid('delete' . $article->getId(), $request->query->get('csrf'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($article);
            $entityManager->flush();
        }

        return $this->redirectToRoute('article_crud_index', [], Response::HTTP_SEE_OTHER);
    }
}
