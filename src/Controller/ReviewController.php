<?php

namespace App\Controller;

use App\Entity\Review;
use App\Form\ReviewType;
use App\Repository\ReviewRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReviewController extends AbstractController
{
    /**
     * This controller allow us to display all the reviews
     *
     * @param ReviewRepository $repository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    #[Route('/review', name: 'review.index', methods: ['GET'])]
    public function index(
        ReviewRepository $repository,
        PaginatorInterface $paginator,
        Request $request
    ): Response {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        if (!$this->getUser()) {
            return $this->redirectToRoute('security.login');
        }

        $review = $paginator->paginate(
            $repository->findAll(),
            $request->query->getInt('page', 1),
            10
        );
        return $this->render('pages/review/index.html.twig', [
            'review' => $review
        ]);
    }
    /************************************************************************* */
    #[Route('/review/publique', 'review.index.public', methods: ['GET'])]
    public function indexPublic(
        ReviewRepository $repository,
        PaginatorInterface $paginator,
        Request $request
    ): Response {
        $review = $paginator->paginate(
            $repository->findPublicReview(null),
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('pages/review/index_public.html.twig', [
            'review' => $review
        ]);
    }

    /********************************************************** */
    /**
     * This controller allow us to create a new review
     *
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Route('/review/nouveau', name: 'review.new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        EntityManagerInterface $manager,
    ): Response {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $review = new Review();
        $form = $this->createForm(ReviewType::class, $review);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $review = $form->getData();

            $manager->persist($review);
            $manager->flush();

            $this->addFlash(
                'success',
                'Votre avis a été enregistré avec succès !'
            );

            return $this->redirectToRoute('review.index');
        }

        return $this->render('pages/review/new.html.twig', [
            'form' => $form->createView()
        ]);
    }
    /************************************************************************ */
    /**
     * This controller allow us to edit reviews
     *
     * @param ReviewRepository $repository
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @param [type] $id
     * @return Response
     */
    #[Route('/review/edition/{id}', 'review.edit', methods: ['GET', 'POST'])]
    public function edit(
        ReviewRepository $repository,
        Request $request,
        EntityManagerInterface $manager,
        $id
    ): Response {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $review = $repository->findOneBy(["id" => $id]);
        $form = $this->createForm(ReviewType::class, $review);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $review = $form->getData();

            $manager->persist($review);
            $manager->flush();

            $this->addFlash(
                'success',
                'Votre avis a été modifié avec succès !'
            );

            return $this->redirectToRoute('review.index');
        }

        return $this->render('pages/review/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }
    /************************************************************* */
    #[Route('/review/show/{id}', 'review.show', methods: ['GET'])]
    public function show(
        ReviewRepository $repository,
        $id
    ): Response {
        $review = $repository->findOneBy(["id" => $id]);


        if (!$review) {
            // Le produit n'existe pas, renvoyez une réponse d'erreur
            $this->addflash(
                'warning',
                'L\'avis en question n\'a pas été trouvé !'
            );
            return $this->redirectToRoute('home.index');
        }

        return $this->render('pages/review/show.html.twig', [
            'review' => $review,
        ]);
    }

    /****************************************************************** */
    /**
     * This controller allow us to delete reviews
     *
     * @param EntityManagerInterface $manager
     * @param REviewRepository $repository
     * @param Review $review
     * @param [type] $id
     * @return Response
     */
    #[Route('/review/suppression/{id}', 'review.delete', methods: ['GET'])]
    public function delete(
        EntityManagerInterface $manager,
        REviewRepository $repository,
        Review $review,
        $id
    ): Response {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $review = $repository->findOneBy(["id" => $id]);
        if (!$review) {
            $this->addflash(
                'warning',
                'L\'avis en question n\'a pas été trouvé !'
            );

            return $this->redirectToRoute('review.index');
        }
        $manager->remove($review);
        $manager->flush();

        $this->addflash(
            'success',
            'l\'avis a été supprimé avec succès !'
        );

        return $this->redirectToRoute('review.index');
    }
}
