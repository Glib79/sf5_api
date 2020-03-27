<?php
declare(strict_types=1);

namespace App\DataTransformer;

use App\DTO\CategoryDto;
use Symfony\Component\HttpFoundation\Request;

class InputCategoryDataTransformer extends BaseDataTransformer
{
    /**
     * Transform Request to CategoryDto and validate dto
     * @param Request $request
     * @return CategoryDto
     */
    public function transform(Request $request): CategoryDto
    {
        $dto = new CategoryDto();
        $data = json_decode($request->getContent(), true);
        
        $dto->name = $data['name'];
        
        $this->validate($dto);
        
        return $dto;
    }
}