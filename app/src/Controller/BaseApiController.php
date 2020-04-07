<?php
declare(strict_types=1);

namespace App\Controller;

use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;

class BaseApiController extends AbstractController
{
    public const DEFAULT_RESPONSE_DATETIME_FORMAT = 'Y-m-d H:i:s';
    
    /**
     * Sets data
     * @param array $data
     * @param int|null $statusCode
     * @param array|null $headers
     * @return JsonResponse
     */
    public function responseWithData(array $data, int $statusCode = Response::HTTP_OK, array $headers = []): JsonResponse
    {
        return $this->response($data, $statusCode, $headers);
    }
    
    /**
     * Sets an error message
     * @param string $error
     * @param int $statusCode
     * @param array|null $headers
     * @return JsonResponse
     */
    public function responseWithError(string $error, int $statusCode, array $headers = []): JsonResponse
    {
        $data = [
            'status' => $statusCode,
            'error'  => $error
        ];

        return $this->response($data, $statusCode, $headers);
    }

    /**
     * Sets an success message
     * @param string $success
     * @param int $statusCode
     * @param array|null $headers
     * @return JsonResponse
     */
    public function responseWithSuccess(string $success, int $statusCode, array $headers = []): JsonResponse
    {
        $data = [
            'status' => $statusCode,
            'success' => $success
        ];

        return $this->response($data, $statusCode, $headers);
    }
   
    /**
     * Returns a JSON response
     * @param array $data
     * @param int $statusCode
     * @param array|null $headers
     * @return JsonResponse
     */
    private function response(array $data, int $statusCode, array $headers = []): JsonResponse
    {
        return new JsonResponse($data, $statusCode, $headers);
    }
}