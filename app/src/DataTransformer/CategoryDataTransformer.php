<?php
declare(strict_types=1);

namespace App\DataTransformer;

use App\DTO\CategoryDto;
use Symfony\Component\HttpFoundation\Request;

class CategoryDataTransformer extends BaseDataTransformer
{
    /**
     * Transform Request to CategoryDto and validate dto
     * @param Request $request
     * @param string $groups
     * @return CategoryDto
     */
    public function transformInput(Request $request, array $groups): CategoryDto
    {
        $dto = new CategoryDto();
        $data = json_decode($request->getContent(), true);
        
        $dto->name = $data['name'];
        
        $this->validate($dto, $groups);
        
        return $dto;
    }
}