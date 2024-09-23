<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Author;
use App\Form\AuthorFormType;
use App\Entity\BlogPost;
use App\Entity\BlogPostHistory;
use App\Form\EntryFormType;

/**
 * @Route("/admin")
 */
class AdminController extends AbstractController
{
    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var \Doctrine\Common\Persistence\ObjectRepository */
    private $authorRepository;

    /** @var \Doctrine\Common\Persistence\ObjectRepository */
    private $blogPostRepository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->blogPostRepository = $entityManager->getRepository(BlogPost::Class);
        $this->blogPostHistoryRepository = $entityManager->getRepository(BlogPostHistory::Class);
        $this->authorRepository = $entityManager->getRepository(Author::Class);
    }

    /**
    * @Route("/author/create", name="author_create")
    */
    public function createAuthorAction(Request $request): Response
    {
        if ($this->authorRepository->findOneByUsername($this->getUser()->getUserName())) {
            $this->addFlash('error', 'Unable to create author, author already exists!');

            return $this->redirectToRoute('homepage');
        }

        $author = new Author();
        $author->setUsername($this->getUser()->getUserName());

        $form = $this->createForm(AuthorFormType::class, $author);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($author);
            $this->entityManager->flush($author);

            $request->getSession()->set('user_is_author', true);
            $this->addFlash('success', 'Congratulations! You are now an author.');

            return $this->redirectToRoute('admin_entries');
        }

        return $this->render('admin/create_author.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
    * @Route("/create-entry", name="admin_create_entry")
    */
    public function createEntryAction(Request $request): Response
    {
        $blogPost = new BlogPost();
        $author = $this->authorRepository->findOneByUsername($this->getUser()->getUserName());
        $blogPost->setAuthor($author);

        $form = $this->createForm(EntryFormType::class, $blogPost);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $blogPostHistory = new BlogPostHistory();
            $blogPostHistory
                ->setAuthor($author)
                ->setAction('Created')
                ->setBlogPost($blogPost)
                ->setTitle($blogPost->getTitle())
                ->setBody($blogPost->getBody());
            $this->entityManager->persist($blogPost);
            $this->entityManager->persist($blogPostHistory);
            $this->entityManager->flush();

            $this->addFlash('success', 'Congratulations! Your post is created');

            return $this->redirectToRoute('admin_entries');
        }

        return $this->render('admin/entry_form.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
    * @Route("/", name="admin_index")
    * @Route("/entries", name="admin_entries")
    */
    public function entriesAction(): Response
    {
        $author = $this->authorRepository->findOneByUsername($this->getUser()->getUserName());

        $blogPosts = [];

        if ($author) {
            $blogPosts = $this->blogPostRepository->findByAuthor($author);
        }

        return $this->render('admin/entries.html.twig', [
            'blogPosts' => $blogPosts
        ]);
    }

    /**
    * @Route("/delete-entry/{entryHash}", name="admin_delete_entry")
    */
    public function deleteEntryAction(string $entryHash): Response
    {
        $blogPost = $this->blogPostRepository->findOneByHash($entryHash);
        $author = $this->authorRepository->findOneByUsername($this->getUser()->getUserName());

        if (!$blogPost || $author !== $blogPost->getAuthor()) {
            $this->addFlash('error', 'Unable to remove entry!');

            return $this->redirectToRoute('admin_entries');
        }

        $blogPostHistory = new BlogPostHistory();
            $blogPostHistory
                ->setAuthor($author)
                ->setAction('Delete')
                ->setBlogPost($blogPost)
                ->setTitle($blogPost->getTitle())
                ->setBody($blogPost->getBody());
        $blogPost->setDeletedAt(new \DateTime());
        $this->entityManager->persist($blogPost);
        $this->entityManager->persist($blogPostHistory);
        $this->entityManager->flush();

        $this->addFlash('success', 'Entry was deleted!');

        return $this->redirectToRoute('admin_entries');
    }

    /**
    * @Route("/update-entry/{entryHash}", name="admin_update_entry")
    */
    public function updateEntryAction(Request $request, string $entryHash): Response
    {
        $blogPost = $this->blogPostRepository->findOneByHash($entryHash);
        $author = $this->authorRepository->findOneByUsername($this->getUser()->getUserName());
        $blogPost->setAuthor($author);

        $form = $this->createForm(EntryFormType::class, $blogPost);
        $form->handleRequest($request);

        // Check is valid
        if ($form->isSubmitted() && $form->isValid()) {
            $blogPost->setUpdatedAt(new \DateTime());
            $this->entityManager->persist($blogPost);
            $blogPostHistory = new BlogPostHistory();
            $blogPostHistory
                ->setAuthor($author)
                ->setAction('Update')
                ->setBlogPost($blogPost)
                ->setTitle($blogPost->getTitle())
                ->setBody($blogPost->getBody());
            $this->entityManager->persist($blogPostHistory);
            $this->entityManager->flush();
            $this->addFlash('success', 'Congratulations! Your post is created');

            return $this->redirectToRoute('admin_entries');
        }

        return $this->render('admin/entry_form.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
    * @Route("/entry-history/{entryHash}", name="admin_history_entry")
    */
    public function entryHistory($entryHash): Response
    {
        $blogPost = $this->blogPostRepository->findOneByHash($entryHash);
        $blogHistorys = $this->blogPostHistoryRepository->getAllPostsHistoryByBlogPostId($blogPost->getId());
    
        return $this->render('admin/entry.html.twig', [
            'blogPost' => $blogPost,
            'blogHistorys' => $blogHistorys
        ]);
    }

    /**
    * @Route("/restore-entry/{entryHash}", name="admin_restore_entry")
    */
    public function restoreEntryAction(string $entryHash): Response
    {
        $blogPost = $this->blogPostRepository->findOneByHash($entryHash);
        $author = $this->authorRepository->findOneByUsername($this->getUser()->getUserName());

        if (!$blogPost || $author !== $blogPost->getAuthor()) {
            $this->addFlash('error', 'Unable to restore entry!');

            return $this->redirectToRoute('admin_entries');
        }

        $blogPostHistory = new BlogPostHistory();
            $blogPostHistory
                ->setAuthor($author)
                ->setAction('Restore')
                ->setBlogPost($blogPost)
                ->setTitle($blogPost->getTitle())
                ->setBody($blogPost->getBody());
        $blogPost->setDeletedAt(null);
        $this->entityManager->persist($blogPost);
        $this->entityManager->persist($blogPostHistory);
        
        $this->entityManager->flush();

        $this->addFlash('success', 'Entry was Restored!');

        return $this->redirectToRoute('admin_entries');
    }
}
