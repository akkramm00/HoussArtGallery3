<?php

namespace App\Controller;

use App\Repository\PostRepository;
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
        $posts = $paginator->paginate(
            $repository->findPublished(),
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('/pages/post/index.html.twig', [
            'posts' => $posts
        ]);
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
