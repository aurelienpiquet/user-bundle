<?php

declare(strict_types=1);

namespace Apb\UserBundle\Controller;

use Apb\UserBundle\Entity\User;
use Apb\UserBundle\Manager\UserManager;
use Doctrine\ORM\NonUniqueResultException;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\View\View;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SignController extends AbstractFOSRestController
{
    public function __construct(
        protected JWTTokenManagerInterface $JWTManager,
        protected UserManager $userManager,
    ) {
    }

    /**
     * Sends a jwt login token. PUBLIC.
     *
     * @throws NonUniqueResultException
     */
    #[Post(path: '/login', name: 'user_bundle_login_jwt')]
    #[Rest\View(statusCode: 200, serializerGroups: [])]
    public function login(Request $request): View
    {
        $user = $this->userManager->login($request->request->all());

        if ($user instanceof User) {
            return $this->view(
                [
                    'token' => $this->JWTManager->create($user),
                ],
                Response::HTTP_OK
            );
        }

        return $this->view($user, response::HTTP_BAD_REQUEST);
    }
}
