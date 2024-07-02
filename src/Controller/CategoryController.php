<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Post;
use App\Repository\PostRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Contracts\Cache\ItemInterface;

#[Route('/categories')]
class CategoryController extends AbstractController
{
    #[Route('/{slug}', name: 'category.index.public', methods: ['GET'])]
    public function index(
        Category $category,
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
            9
        );

        return $this->render('pages/category/index.html.twig', [
            'category' => $category,
            'posts' => $posts
        ]);
    }
}
