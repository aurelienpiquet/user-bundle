<?php

declare(strict_types=1);

namespace Apb\UserBundle\Controller;

use Apb\UserBundle\Form\PasswordEditType;
use Apb\UserBundle\Form\RequestPasswordCreateType;
use Apb\UserBundle\Manager\UserManager;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

#[Route('/api/v1/public/forgotten-password')]
#[OA\Tag(name: 'ForgottenPassword')]
class ResetPasswordController extends AbstractFOSRestController
{
    public function __construct(
        protected UserManager $userManager,
    ) {
    }

    /**
     * Sends a mail to reset password.
     *
     * @param Request $request
     *
     * @return View
     *
     * @throws \Exception
     */
    #[OA\Response(
        response: 204,
        description: 'Email send to the user successfully',
        content: []
    )]
    #[OA\RequestBody(
        content: new OA\JsonContent(ref: new Model(type: RequestPasswordCreateType::class))
    )]
    #[Post(path: '/request', name: 'apb_user_bundle_forgotten_password_request')]
    #[Rest\View(statusCode: 204, serializerGroups: [])]
    public function request(Request $request): View
    {
        return $this->view($this->userManager->requestForgottenPassword($request->request->all()), Response::HTTP_NO_CONTENT);
    }

    /**
     * Modifies the password of a user.
     *
     * @throws \Exception
     */
    #[OA\Response(
        response: 204,
        description: 'Password modified successfully',
        content: []
    )]
    #[OA\RequestBody(
        content: new OA\JsonContent(ref: new Model(type: PasswordEditType::class))
    )]
    #[Post(path: '/resetting/{token}', name: 'apb_user_bundle_forgotten_password_resetting')]
    #[Rest\View(statusCode: 204, serializerGroups: [])]
    public function reset(Request $request, string $token): View
    {
        return $this->view($this->userManager->changePassword($request->request->all(), $token), Response::HTTP_NO_CONTENT);
    }
}
