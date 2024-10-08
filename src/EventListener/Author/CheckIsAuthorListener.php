<?php

namespace App\EventListener\Author;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use App\Entity\Author;

class CheckIsAuthorListener
{
    /** @var RouterInterface */
    protected $router;

    /** @var SessionInterface */
    protected $session;

    /** @var TokenStorageInterface */
    private $tokenStorage;

    /** @var EntityManagerInterface */
    private $entityManager;

    
    public function __construct(RouterInterface $router, SessionInterface $session, TokenStorageInterface $tokenStorage, EntityManagerInterface $entityManager) {
        $this->router = $router;
        $this->session = $session;
        $this->tokenStorage = $tokenStorage;
        $this->entityManager = $entityManager;
    }

    public function onKernelController(ControllerEvent $event): void
    {
        // Don't add to the flasher if the current path does not begin with /admin
        if (!preg_match('/^\/admin/i', $event->getRequest()->getPathInfo())) {
            return;
        }
        $user = $this->tokenStorage->getToken()->getUser();
        if (null === $user) {
            return;
        }

        // Use the session to exit this listener early, if the relevant checks have already been made
        if (true === $this->session->get('user_is_author')) {
            return;
        }

        $route = $this->router->generate('author_create');

        // Check we are not already attempting to create an author!
        if (0 === strpos($event->getRequest()->getPathInfo(), $route)) {
            return;
        }

        // Check if authenticated user has an author associated with them.
        if ($author = $this->entityManager
            ->getRepository(Author::class)
            ->findOneByUsername($user->getUsername())
        ) {
            $this->session->set('user_is_author', true);
        }

        if (true === $this->session->get('user_is_author')) {
            return;
        }

        if (!$author && $this->session->get('pending_user_is_author')) {
            $this->session->getFlashBag()->add(
                'warning',
                'Your author access is being set up, this may take up to 30 seconds. Please try again shortly.'
            );

            $route = $this->router->generate('homepage');
        } else {
            $this->session->getFlashBag()->add(
                'warning',
                'You cannot access the author section until you become an author. Please complete the form below to proceed.'
            );
        }

        $event->setController(function () use ($route) {
            return new RedirectResponse($route);
        });
    }
}
