<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProductsRepository;
use App\Repository\ColletionRepository;
use App\Repository\ReviewRepository;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home.index', methods: ['GET'])]
    public function index(
        ProductsRepository $productsRepository,
        ColletionRepository $colletionRepository,
        ReviewRepository $reviewRepository
    ): Response {
        return $this->render('pages/home/index.html.twig', [
            'products' => $productsRepository->findPublicProducts(4),
            'colletion' => $colletionRepository->findPublicColletion(4),
            'review' => $reviewRepository->findPublicReview(4),
        ]);
    }
}
