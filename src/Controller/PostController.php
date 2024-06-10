<?php

namespace App\Controller;

use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{
    #[Route('/post', name: 'post.index', methods: ['GET'])]
    public function index(
        PostRepository $postRepository
    ): Response {

        $posts = $postRepository->findAll();


        return $this->render('/pages/post/index.html.twig', [
            'posts' => $posts
        ]);
    }
}
