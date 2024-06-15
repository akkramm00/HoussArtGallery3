<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Contracts\Cache\ItemInterface;

class PostController extends AbstractController
{
    /**
     * This method allow us to publish all posts with STATE_PUBLISHED
     *
     * @param PostRepository $postRepository
     * @return Response
     */
    #[Route('/post', name: 'post.index', methods: ['GET'])]
    public function index(
        PostRepository $repository,
        PaginatorInterface $paginator,
        Request $request
    ): Response {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $post = $paginator->paginate(
            $repository->findPublished(),
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('/pages/post/index.html.twig', [
            'post' => $post
        ]);
    }
    /************************************************************* */
    #[Route('/post/nouveau', 'post.new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        EntityManagerInterface $manager
    ): Response {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $post = $form->getData();

            $manager->persist($post);
            $manager->flush();
            $this->addFlash(
                'success',
                'Votre Article a été créé avec succès !'
            );

            return $this->redirectToRoute('post.index');
        }


        return $this->render('pages/post/new.html.twig', [
            'form' => $form->createView()
        ]);
    }
    /************************************************************* */
    #[Route('/post/edit/{id}', 'post.edit', methods: ['GET', 'POST'])]
    public function edit(
        Post $post,
        PostRepository $repository,
        Request $request,
        EntityManagerInterface $manager,
        $id
    ): Response {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $post = $repository->findOneBy(["id" => $id]);
        $form = $this->createForm(PostType::class, $post);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $post = $form->getData();

            $manager->persist($post);
            $manager->flush();

            $this->addFlash(
                'success',
                'L\'article a été modifié avec succès !'
            );

            return $this->redirectToRoute('post.index');
        }


        return $this->render('pages/post/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }
    /************************************************************* */
    #[Route('/post/suppression/{id}', 'post.delete', methods: ['GET'])]
    public function delete(
        EntityManagerInterface $manager,
        PostRepository $repository,
        Post $post,
        $id
    ): Response {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $post = $repository->findOneBy(["id" => $id]);
        if (!$post) {
            $this->addFlash(

                "warning",
                "L'article en question n'a pas été trouvé !"
            );

            return $this->redirectToRoute('post.index');
        }
        $manager->remove($post);
        $manager->flush();

        $this->addFlash(
            "success",
            "L'article a été suprimé avec succès !"
        );

        return $this->redirectToRoute('post.index');
    }
    /************************************************************* */
    #[Route('/post/publique', 'post.index.public', methods: ['GET'])]
    public function indexPublic(
        PostRepository $repository,
        PaginatorInterface $paginator,
        Request $request
    ): Response {
        $cache = new FilesystemAdapter();
        $data = $cache->get('posts', function (ItemInterface $item) use ($repository) {
            $item->expiresAfter(15);
            return $repository->findPublished();
        });

        $posts = $paginator->paginate(
            $data,
            $request->query->getInt('page', 1),
            12
        );


        return $this->render('pages/post/index_public.html.twig', [
            'posts' => $posts
        ]);
    }
    /************************************************************* */
    #[Route('/post/show/{id}', 'post.show', methods: ['GET'])]
    public function show(
        PostRepository $repository,
        $id
    ): Response {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $posts = $repository->findOneBy(["id" => $id]);

        if (!$posts) {
            // L article n'existe pas, renvoyez une réponse d'erreur
            $this->addflash(
                'warning',
                'L\'article en question n\'a pas été trouvé !'
            );
            return $this->redirectToRoute('home.index');
        }


        return $this->render('pages/post/show.html.twig', [
            'posts' => $posts,
        ]);
    }
}
