<?php
declare(strict_types=1);

namespace App\Controller;

use App\DataTransformer\InputUserDataTransformer;
use App\DTO\UserDto;
use App\Service\UserManager;
use App\Support\ValidationException;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class AuthController extends BaseApiController
{
    /**
     * @var InputUserDataTransformer
     */
    private $inputUserDataTransformer;
    
    /**
     * @var UserManager
     */
    private $userManager;
    
    /**
     * AuthController constructor
     * @param InputUserDataTransformer $inputUserDataTransformer
     * @param UserManager $userManager
     */
    public function __construct(InputUserDataTransformer $inputUserDataTransformer, UserManager $userManager)
    {
        $this->inputUserDataTransformer = $inputUserDataTransformer;
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
        try {
            /** @var UserDto */
            $dto = $this->inputUserDataTransformer->transform($request);
            
            $this->userManager->createUser($dto);
            
            return $this->responseWithSuccess(
                sprintf('User %s successfully created', $dto->email), 
                Response::HTTP_CREATED
            );
        } catch (ValidationException $e) {
            return $this->responseWithError($e->getMessage(), Response::HTTP_BAD_REQUEST);
        } catch (Exception $e) {
            return $this->responseWithError('User can not be created', Response::HTTP_BAD_REQUEST);
        }
    }
}