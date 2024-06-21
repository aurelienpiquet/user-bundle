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
use OpenApi\Attributes as OA;

#[OA\Tag(name: 'Profile')]
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
    #[OA\Response(
        response: 200,
        description: 'User connected profile',
        content: []
    )]
    #[Get(path: '', name: 'apb_user_bundle_me_get')]
    #[Rest\View(statusCode: 200, serializerGroups: ['apb_user_details', 'apb_user'])]
    public function fetch(): View
    {
        return $this->view($this->userManager->get(), response::HTTP_OK);
    }
}
