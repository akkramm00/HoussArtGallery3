<?php

namespace App\Controller;

use App\Entity\Products;
use App\Entity\ImageProducts;
use App\Repository\ProductsRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\ProductsType;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Contracts\Cache\ItemInterface;


class ProductsController extends AbstractController
{
    /**
     * This controller dispaly all products
     *
     * @param ProductsRepository $repository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */

    #[Route('/products', name: 'products', methods: ['GET'])]
    public function index(
        ProductsRepository $repository,
        PaginatorInterface $paginator,
        Request $request
    ): Response {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $products = $paginator->paginate(
            $repository->findBy(['user' => $this->getUser()]),
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('pages/products/index.html.twig', [
            'products' => $products
        ]);
    }
    /************************************************************************* */
    #[Route('/products/publique', 'products.index.public', methods: ['GET'])]
    /**
     * this Controller allow us to display all off products public
     *
     * @param ProductsRepository $repository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    public function indexPublic(
        ProductsRepository $repository,
        PaginatorInterface $paginator,
        Request $request
    ): Response {

        if (!$this->getUser()) {
            return $this->redirectToRoute('security.login');
        }

        $cache = new FilesystemAdapter();
        $data = $cache->get('products', function (ItemInterface $item) use ($repository) {
            $item->expiresAfter(15);
            return $repository->findPublicProducts(null);
        });

        $products = $paginator->paginate(
            $data,
            $request->query->getInt('page', 1),
            12
        );

        return $this->render('pages/products/index_public.html.twig', [
            'products' => $products
        ]);
    }
    /***************************************************************************************** */
    /**
     * This controller allow us to create a new product
     *
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    private $imageDir;

    public function __construct(string $imageDir)
    {
        $this->imageDir = $imageDir;
    }
    #[Route('/products/nouveau', 'products.new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        EntityManagerInterface $manager
    ): Response {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $products = new Products();
        $form = $this->createForm(ProductsType::class, $products);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $products = $form->getData();
            $products->setUser($this->getUser());

            // Gestion des images téléchargées
            $images = $form->get('images')->getData(); // Assurez-vous que votre formulaire retourne un tableau d'instances de `Symfony\Component\HttpFoundation\File\UploadedFile`
            foreach ($images as $image) {
                $imageProducts = new ImageProducts();
                $imageProducts->setImageFile($image);


                $imageProducts->setProduct($products); // Associez l'image au produit
                $manager->persist($imageProducts); // Persistez l'entité ImageProduct
            }

            $manager->persist($products);
            $manager->flush();

            $this->addFlash(
                'success',
                'Votre produit a été créé avec succès !'
            );

            return $this->redirectToRoute('products');
        }

        return $this->render('pages/products/new.html.twig', [
            'form' => $form->createView()
        ]);
    }
    /******************************************************************************************** */

    /**
     * This controller allow us to edit products
     *
     * @param ProductsRepository $repository
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @param [type] $id
     * @return Response
     */
    #[Route('/products/edition/{id}', 'products.edit', methods: ['GET', 'POST'])]
    public function edit(
        Products $products,
        ProductsRepository $repository,
        Request $request,
        EntityManagerInterface $manager,
        $id
    ): Response {
        //on vérife si l'utilisateur peut éditer avec le voter
        $this->denyAccessUnlessGranted('ROLE_USER');
        // $this->denyAccessUnlessGranted('ROLE_PRODUCT_ADMIN');
        $products = $repository->findOneBy(["id" => $id]);
        $form = $this->createForm(ProductsType::class, $products);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $products = $form->getData();

            $manager->persist($products);
            $manager->flush();

            $this->addFlash(
                'success',
                'Votre produit a été modifié avec succès !'
            );

            return $this->redirectToRoute('products');
        }

        return $this->render('pages/products/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }
    /************************************************************************ */
    /**
     * This Controller allow us to delete Products .
     *
     * @param EntityManagerInterface $manager
     * @param ProductsRepository $repository
     * @param Products $products
     * @param [type] $id
     * @return Response
     */
    #[Route('/products/suppression/{id}', 'products.delete', methods: ['GET'])]
    public function delete(
        EntityManagerInterface $manager,
        ProductsRepository $repository,
        Products $products,
        $id
    ): Response {
        // $this->denyAccessUnlessGranted('PRODUCT_DELETE', $products);
        $this->denyAccessUnlessGranted('ROLE_PRODUCT_ADMIN');
        $products = $repository->findOneBy(["id" => $id]);
        if (!$products) {
            $this->addflash(
                'warning',
                'Le produit en question n\'a pas été trouvé !'
            );

            return $this->redirectToRoute('products');
        }
        $manager->remove($products);
        $manager->flush();

        $this->addflash(
            'success',
            'Le produits a été supprimé avec succès !'
        );

        return $this->redirectToRoute('products');
    }
    /************************************************************************* */
    #[Route('/products/show/{id}', 'products.show', methods: ['GET'])]
    public function show(
        ProductsRepository $repository,
        $id
    ): Response {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $products = $repository->findOneBy(["id" => $id]);


        if (!$products) {
            // Le produit n'existe pas, renvoyez une réponse d'erreur
            $this->addflash(
                'warning',
                'Le produit en question n\'a pas été trouvé !'
            );
            return $this->redirectToRoute('home.index');
        }

        return $this->render('pages/products/show.html.twig', [
            'products' => $products,
        ]);
    }
}
