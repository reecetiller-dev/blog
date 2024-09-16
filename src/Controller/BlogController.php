<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\BlogPostRepository;
use App\Repository\AuthorRepository;

use Symfony\Component\HttpFoundation\Request;

class BlogController extends AbstractController
{
    /** @var integer */
    const POST_LIMIT = 5;

    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var \Doctrine\Common\Persistence\ObjectRepository */
    private $authorRepository;

    /** @var \Doctrine\Common\Persistence\ObjectRepository */
    private $blogPostRepository;

    public function __construct(EntityManagerInterface $entityManager, BlogPostRepository $blogPostRepository, AuthorRepository $authorRepository)
    {
        $this->entityManager = $entityManager;
        $this->blogPostRepository = $blogPostRepository;
        $this->authorRepository = $authorRepository;
    }

    /**
    * @Route("/", name="homepage")
    * @Route("/entries", name="entries")
    */
    public function entriesAction(Request $request): Response
    {
        return $this->render('blog/entries.html.twig', [
            'blogPosts' => $this->blogPostRepository->getAllPosts()
        ]);
    }

    /**
    * @Route("/entry/{entryHash}", name="entry")
    */
    public function entryAction(string $entryHash): Response
    {
        $blogPost = $this->blogPostRepository->findOneByHash($entryHash);

        if (!$blogPost) {
            $this->addFlash('error', 'Unable to find entry!');

            return $this->redirectToRoute('entries');
        }

        return $this->render('blog/entry.html.twig', array(
            'blogPost' => $blogPost
        ));
    }

    /**
    * @Route("/author/{name}", name="author")
    */
    public function authorAction($name): Response
    {
        $author = $this->authorRepository->findOneByUsername($name);

        if (!$author) {
            $this->addFlash('error', 'Unable to find author!');
            return $this->redirectToRoute('entries');
        }

        return $this->render('blog/author.html.twig', [
            'author' => $author
        ]); 
    }
}
