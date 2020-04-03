<?php
declare(strict_types=1);

namespace App\DataTransformer;

use App\DTO\UserDto;
use Symfony\Component\HttpFoundation\Request;

class UserDataTransformer extends BaseDataTransformer
{
    /**
     * Transform Request to UserDto
     * @param Request $request
     * @return UserDto
     */
    public function transformInput(Request $request): UserDto
    {
        $dto = new UserDto($this->serializer, $this->validator);
        $data = json_decode($request->getContent(), true);
        
        $dto->email = $data['email'];
        $dto->password = $data['password'];
        
        return $dto;
    }
}