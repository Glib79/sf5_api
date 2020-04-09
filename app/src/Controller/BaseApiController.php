<?php
declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class BaseApiController extends AbstractController
{
    /**
     * Prepares and returns a JSON response
     * @param int $statusCode
     * @param string $message
     * @param array|null $data
     * @param array|null $meta
     * @param array|null $headers
     * @return JsonResponse
     */
    public function response(
        int $statusCode, 
        string $message, 
        array $data = [], 
        array $meta = [], 
        array $headers = []
    ): JsonResponse
    {
        $response = [
            'data'    => $data,
            'message' => $message,
            'meta'    => $meta
        ];
        
        return new JsonResponse($response, $statusCode, $headers);
    }
}