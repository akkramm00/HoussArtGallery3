<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProductsRepository;
use App\Repository\ColletionRepository;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home.index', methods: ['GET'])]
    public function index(
        ProductsRepository $productsRepository,
        ColletionRepository $colletionRepository,
    ): Response {
        return $this->render('pages/home/index.html.twig', [
            'products' => $productsRepository->findPublicProducts(4),
            'colletion' => $colletionRepository->findPublicColletion(4),
        ]);
    }
}
