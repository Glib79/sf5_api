<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Service\UserManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class AuthController extends BaseApiController
{
    /**
     * @var UserManager
     */
    private $userManager;
    
    /**
     * AuthController constructor
     * @param UserManager $userManager
     */
    public function __construct(UserManager $userManager)
    {
        $this->userManager = $userManager;
    }
    
    /**
     * Login user
     * @param UserInterface $user
     * @param JWTTokenManagerInterface $JWTManager
     * @return JsonResponse
     * @Route("/auth/login_check", name="login_check", methods={"POST"})
     */
    public function getTokenUser(UserInterface $user)
    {
        return $this->responseWithData(
            [
                'token' => $this->userManager->generateJWTToken($user)
            ],
            Response::HTTP_OK
        );
    }
    
    /**
     * Register endpoint
     * @param Request $request
     * @return JsonResponse
     * @Route("/auth/register", name="register", methods={"POST"})
     */
    public function register(Request $request): JsonResponse
    {
        
        $request = $this->transformJsonBody($request);
        $password = $request->get('password');
        $email = $request->get('email');

        if (empty($password) || empty($email)) {
            return $this->responseWithError("Invalid Email or Password", Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $this->userManager->createUser($email, $password);
            
            return $this->responseWithSuccess(
                sprintf('User %s successfully created', $email), 
                Response::HTTP_CREATED
            );
        } catch (Exception $e) {
            return $this->responseWithError(
                sprintf('User %s can not be created', $email),
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }
    }
}