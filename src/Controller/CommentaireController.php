<?php

namespace App\Controller;

use DateTime;
use App\Entity\Article;
use App\Entity\Commentaire;
use Symfony\Component\Mime\Email;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommentaireController extends AbstractController {
    /**
     * @Route("/article/{id}/commentaire/new", name="post_comment", methods={"POST"})
     * @IsGranted("ROLE_USER")
     */
    public function post(Article $article, Request $request, EntityManagerInterface $em, MailerInterface $mailer) {
        if ($this->isCsrfTokenValid('post-comment', $request->query->get('csrf'))) {

            $commentaire = new Commentaire;
            $commentaire->setDatePublication(new DateTime);
            $commentaire->setNote(0);
            $commentaire->setContenu($request->request->get('contenu'));
            $commentaire->setAuteur($this->getUser());
            $commentaire->setArticle($article);

            $em->persist($commentaire);
            $em->flush();

            $mailer->send(
                (new Email)
                    ->from('Blogmania <test.symfony@2alheure.fr>')
                    ->to($article->getAuteur()->getEmail())
                    ->text('Un utilisateur a commenté votre article "' . $article->getTitre() . '".')
            );

            return $this->json($commentaire, 201, [], [
                'groups' => ['to-serialize']
            ]);
        } else return new Response;
    }

    /**
     * @Route("/commentaire/{id}/delete", name="delete_comment", methods={"DELETE"})
     * @IsGranted("ROLE_MODO")
     */
    public function delete(Commentaire $commentaire, Request $request, EntityManagerInterface $em) {
        if ($this->isCsrfTokenValid('delete-comment', $request->query->get('csrf'))) {
            $em->remove($commentaire);
            $em->flush();
        }

        return new Response;
    }

    /**
     * @Route("/commentaire/{id}/up", name="upvote_comment")
     * @IsGranted("ROLE_USER")
     */
    public function upVote(Commentaire $commentaire, EntityManagerInterface $em) {
        $commentaire->upVote();

        $em->persist($commentaire);
        $em->flush();

        return $this->redirectToRoute('article_crud_show', [
            'id' => $commentaire->getArticle()->getId()
        ]);
    }

    /**
     * @Route("/commentaire/{id}/down", name="downvote_comment")
     * @IsGranted("ROLE_USER")
     */
    public function downVote(Commentaire $commentaire, EntityManagerInterface $em) {
        $commentaire->downVote();

        $em->persist($commentaire);
        $em->flush();

        return $this->redirectToRoute('article_crud_show', [
            'id' => $commentaire->getArticle()->getId()
        ]);
    }
}
