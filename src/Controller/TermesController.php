<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TermesController extends AbstractController
{
    #[Route('/termes', name: 'termes.index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('pages/termes/index.html.twig');
    }
    /************************************************************* */
    #[Route('/politiques', name: 'politiques.index', methods: ['GET'])]
    public function politiques(): Response
    {
        return $this->render('pages/termes/politiques.html.twig');
    }
}
