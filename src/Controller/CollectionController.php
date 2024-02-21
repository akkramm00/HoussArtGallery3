<?php

namespace App\Controller;

use App\Entity\Colletion;
use App\Form\CollectionType;
use App\Repository\ColletionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CollectionController extends AbstractController
{
    /**
     * THis controller display All collections
     *
     * @param ColletionRepository $repository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    #[Route('/collection', name: 'collection.index', methods: ['GET'])]
    public function index(
        ColletionRepository $repository,
        PaginatorInterface $paginator,
        Request $request
    ): Response {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $colletion = $paginator->paginate(
            $repository->findBy(['user' => $this->getUser()]),
            $request->query->getInt('page', 1),
            10
        );
        return $this->render('pages/collection/index.html.twig', [
            'colletion' => $colletion,
        ]);
    }
    /************************************************************************ */
    /**
     * This controller allow us to create a new collection
     *
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Route('/collection/nouveau', 'collection.new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        EntityManagerInterface $manager,
    ): Response {
        $this->denyAccessUnlessGranted('ROLE_PRODUCT_ADMIN');
        $colletion = new Colletion();
        $form = $this->createForm(CollectionType::class, $colletion);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $colletion = $form->getData();
            $colletion->setUser($this->getUser());

            $manager->persist($colletion);
            $manager->flush();

            $this->addFlash(
                'success',
                'Votre collection a été créé avec succès !'
            );

            return $this->redirectToRoute('collection.index');
        }

        return $this->render('pages/collection/new.html.twig', [
            'form' => $form->createView()
        ]);
    }
    /*********************************************************** */
    /**
     * This controller allow us to edit a collection
     *
     * @param ColletionRepository $repository
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @param [type] $id
     * @return Response
     */
    #[Route('/collection/edition/{id}', 'collection.edit', methods: ['GET', 'POST'])]
    public function edit(
        Colletion $colletion,
        ColletionRepository $repository,
        Request $request,
        EntityManagerInterface $manager,
        $id
    ): Response {
        $this->denyAccessUnlessGranted('COLLECTION_EDIT', $colletion);
        // $this->denyAccessUnlessGranted('ROLE_PRODUCT_ADMIN');
        $colletion = $repository->findOneBy(["id" => $id]);
        $form = $this->createForm(CollectionType::class, $colletion);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $colletion = $form->getData();

            $manager->persist($colletion);
            $manager->flush();

            $this->addFlash(
                'success',
                'Votre collection a été modifié avec succès !'
            );

            return $this->redirectToRoute('collection.index');
        }
        return $this->render('pages/collection/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }
    /************************************************************************ */
    /**
     * This controller allow us to delete collections
     *
     * @param EntityManagerInterface $manager
     * @param ColletionRepository $repository
     * @param Colletion $colletion
     * @param [type] $id
     * @return Response
     */
    #[Route('/collection/suppression/{id}', 'collection.delete', methods: ['GET', 'POST'])]
    public function delete(
        EntityManagerInterface $manager,
        ColletionRepository $repository,
        Colletion $colletion,
        $id
    ): Response {
        // $this->denyAccessUnlessGranted('ROLE_PRODUCT_ADMIN');
        $this->denyAccessUnlessGranted('COLLECTION_DELETE', $colletion);
        $colletion = $repository->findOneBy(["id" => $id]);
        if (!$colletion) {
            $this->addflash(
                'warning',
                'La collection en question n\'a pas été trouvée !'
            );

            return $this->redirectToRoute('collection.index');
        }
        $manager->remove($colletion);
        $manager->flush();

        $this->addflash(
            'success',
            'La collection a été supprimée avec succès !'
        );

        return $this->redirectToRoute('collection.index');
    }
    /************************************************************************* */
    #[Route('/collection/show/{id}', 'collection.show', methods: ['GET'])]
    public function show(
        ColletionRepository $repository,
        $id
    ): Response {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $colletion = $repository->findOneBy(["id" => $id]);


        if (!$colletion) {
            // Le produit n'existe pas, renvoyez une réponse d'erreur
            $this->addflash(
                'warning',
                'Le produit en question n\'a pas été trouvé !'
            );
            return $this->redirectToRoute('home.index');
        }

        return $this->render('pages/collection/show.html.twig', [
            'colletion' => $colletion,
        ]);
    }
}
