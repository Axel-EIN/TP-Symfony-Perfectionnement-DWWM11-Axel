<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Commentaire;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class CommentaireController extends AbstractController {
    /**
     * @Route("/article/{id}/commentaire/new", name="post_comment")
     * @IsGranted("ROLE_USER")
     */
    public function post(Article $article, Request $request, EntityManagerInterface $em): Response {

        $commentaire = new Commentaire;
        $commentaire->setDatePublication(new DateTime);
        $commentaire->setContenu($request->request->get('commentaire')['contenu']);
        $commentaire->setAuteur($this->getUser());
        $commentaire->setArticle($article);

        $em->persist($commentaire);
        $em->flush();

        return $this->redirectToRoute('article_crud_show', [
            'id' => $article->getId()
        ]);
    }

    /**
     * @Route("/commentaire/{id}/delete", name="delete_comment")
     * @IsGranted("ROLE_MODO")
     */
    public function delete(Commentaire $commentaire, Request $request, EntityManagerInterface $em) {
        $article_id = $commentaire->getArticle()->getId();

        if ($this->isCsrfTokenValid('delete' . $commentaire->getId(), $request->query->get('csrf'))) {
            $em->remove($commentaire);
            $em->flush();
        }

        return $this->redirectToRoute('article_crud_show', [
            'id' => $article_id
        ]);
    }
}
