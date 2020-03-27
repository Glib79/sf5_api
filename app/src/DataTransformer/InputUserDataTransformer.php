<?php
declare(strict_types=1);

namespace App\DataTransformer;

use App\DTO\UserDto;
use Symfony\Component\HttpFoundation\Request;

class InputUserDataTransformer extends BaseDataTransformer
{
    /**
     * Transform Request to UserDto and validate dto
     * @param Request $request
     * @return UserDto
     */
    public function transform(Request $request): UserDto
    {
        $dto = new UserDto();
        $data = json_decode($request->getContent(), true);
        
        $dto->email = $data['email'];
        $dto->password = $data['password'];
        
        $this->validate($dto);
        
        return $dto;
    }
}