<?php

namespace App\Controller;

use App\Entity\ImageProducts;
use App\Form\ImageProductsType;
use App\Repository\ImageProductsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ImageProductsController extends AbstractController
{
    #[Route('/image/products', name: 'image_products', methods: ['GET'])]
    public function index(
        ImageProductsRepository $repository,
        PaginatorInterface $paginator,
        Request  $request
    ): Response {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $image_products =  $paginator->paginate(
            $repository->findAll(),
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('pages/image_products/index.html.twig', [
            'image_products' => $image_products
        ]);
    }
    /****************************************************** */
    #[Route('/image/products/nouveau', 'images_products.new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        EntityManagerInterface $manager
    ): Response {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $image_products = new ImageProducts();
        $form = $this->createForm(ImageProductsType::class, $image_products);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $images_products = $form->getData();


            $manager->persist($images_products);
            $manager->flush();

            $this->addFlash(
                'success',
                'Votre produit a été créé avec succès !'
            );

            return $this->redirectToRoute('images/products');
        }

        return $this->render('pages/image_products/new.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
