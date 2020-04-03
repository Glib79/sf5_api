<?php
declare(strict_types=1);

namespace App\DataTransformer;

use App\DTO\UserDto;
use Symfony\Component\HttpFoundation\Request;

class UserDataTransformer extends BaseDataTransformer
{
    /**
     * Transform Request to UserDto and validate dto
     * @param Request $request
     * @param array $groups
     * @return UserDto
     */
    public function transformInput(Request $request, array $groups): UserDto
    {
        $dto = new UserDto();
        $data = json_decode($request->getContent(), true);
        
        $dto->email = $data['email'];
        $dto->password = $data['password'];
        
        $this->validate($dto, $groups);
        
        return $dto;
    }
}