<?php

namespace App\Controller;

use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
        PostRepository $postRepository
    ): Response {

        $posts = $postRepository->findPublished();


        return $this->render('/pages/post/index.html.twig', [
            'posts' => $posts
        ]);
    }
}
