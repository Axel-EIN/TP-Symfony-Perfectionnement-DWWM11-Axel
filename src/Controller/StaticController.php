<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StaticController extends AbstractController {
    /**
     * @Route("/", name="home")
     */
    public function home(): Response {
        return $this->render('static/home.html.twig');
    }

    /**
     * @Route("/cgu", name="cgu")
     */
    public function cgu(): Response {
        return $this->render('static/cgu.html.twig');
    }
}
