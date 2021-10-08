<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\UtilisateurType;
use App\Repository\UtilisateurRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * @Route("/utilisateur/crud")
 * @IsGranted("ROLE_ADMIN")
 */
class UtilisateurCRUDController extends AbstractController {
    /**
     * @Route("/", name="utilisateur_crud_index", methods={"GET"})
     */
    public function index(UtilisateurRepository $utilisateurRepository): Response {
        return $this->render('utilisateur_crud/index.html.twig', [
            'utilisateurs' => $utilisateurRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="utilisateur_crud_new", methods={"GET","POST"})
     */
    public function new(Request $request, UserPasswordHasherInterface $userPasswordHasherInterface): Response {
        $utilisateur = new Utilisateur();
        $form = $this->createForm(UtilisateurType::class, $utilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $utilisateur->setPassword(
                $userPasswordHasherInterface->hashPassword(
                    $utilisateur,
                    $form->get('motDePasse')->getData()
                )
            );

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($utilisateur);
            $entityManager->flush();

            return $this->redirectToRoute('utilisateur_crud_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('utilisateur_crud/new.html.twig', [
            'utilisateur' => $utilisateur,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="utilisateur_crud_show", methods={"GET"})
     */
    public function show(Utilisateur $utilisateur): Response {
        return $this->render('utilisateur_crud/show.html.twig', [
            'utilisateur' => $utilisateur,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="utilisateur_crud_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Utilisateur $utilisateur, UserPasswordHasherInterface $userPasswordHasherInterface): Response {

        $ancien_mdp = $utilisateur->getMotDePasse();

        $utilisateur->setMotDePasse('******');

        $form = $this->createForm(UtilisateurType::class, $utilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('motDePasse')->getData() != '******') {
                $utilisateur->setPassword(
                    $userPasswordHasherInterface->hashPassword(
                        $utilisateur,
                        $form->get('motDePasse')->getData()
                    )
                );
            } else {
                $utilisateur->setMotDePasse($ancien_mdp);
            }

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('utilisateur_crud_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('utilisateur_crud/edit.html.twig', [
            'utilisateur' => $utilisateur,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="utilisateur_crud_delete", methods={"POST"})
     */
    public function delete(Request $request, Utilisateur $utilisateur): Response {
        if ($this->isCsrfTokenValid('delete' . $utilisateur->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($utilisateur);
            $entityManager->flush();
        }

        return $this->redirectToRoute('utilisateur_crud_index', [], Response::HTTP_SEE_OTHER);
    }
}
