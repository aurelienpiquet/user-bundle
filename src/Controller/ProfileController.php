<?php

declare(strict_types=1);

namespace Apb\UserBundle\Controller;

use Apb\UserBundle\Manager\UserManager;
use Doctrine\ORM\NonUniqueResultException;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Response;

#[Route(path: '/api/v1/users/me')]
class ProfileController extends AbstractFOSRestController
{
    public function __construct(
        protected UserManager $userManager,
    ) {
    }

    /**
     * Returns connected user details. ROLE_USER.
     *
     * @throws NonUniqueResultException
     */
    #[Get(path: '', name: 'user_bundle_me_get')]
    #[Rest\View(statusCode: 200, serializerGroups: ['user_details', 'user'])]
    public function fetch(): View
    {
        return $this->view($this->userManager->get(), response::HTTP_OK);
    }
}
