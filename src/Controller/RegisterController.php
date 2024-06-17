<?php

declare(strict_types=1);

namespace Apb\UserBundle\Controller;

use Apb\UserBundle\Manager\UserManager;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

#[Route(path: '/api/v1')]
class RegisterController extends AbstractFOSRestController
{
    public function __construct(
        protected UserManager $userManager,
    ) {
    }

    /**
     * Register a new user, and sends him a mail with a code.
     *
     * @param Request $request
     *
     * @return View
     *
     * @throws \Exception
     */
    #[Post(path: '/public/register', name: 'user_bundle_register')]
    #[Rest\View(statusCode: 201, serializerGroups: ['user'])]
    public function register(Request $request): View
    {
        return $this->view($this->userManager->register($request->request->all()), response::HTTP_CREATED);
    }
}
