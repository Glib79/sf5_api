<?php
declare(strict_types=1);

namespace App\DataTransformer;

use DateTime;
use App\DTO\CategoryDto;
use Symfony\Component\HttpFoundation\Request;

class CategoryDataTransformer extends BaseDataTransformer
{
    /**
     * Transform Request to CategoryDto
     * @param Request $request
     * @return CategoryDto
     */
    public function transformInput(Request $request): CategoryDto
    {
        $dto = new CategoryDto($this->serializer, $this->validator);
        $data = json_decode($request->getContent(), true);
        
        $dto->name = $data['name'];
        
        return $dto;
    }
    
    /**
     * Transform array from database to array ready for output
     * @param array $data
     * @param array $groups
     * @return array
     */
    public function transformList(array $data, array $groups): array
    {
        $output = [];
        foreach ($data as $object) {
            $output[] = $this->transformOutput($object, $groups);
        }
        
        return $output;
    }
    
    /**
     * Transform array from database to array ready for output
     * @param array $category
     * @param array $groups
     * @return array
     */
    public function transformOutput(array $category, array $groups): array
    {
        $dto = new CategoryDto($this->serializer, $this->validator);
        $dto->id = $category['id'];
        $dto->name = $category['name'];
        $dto->createdAt = new DateTime($category['created_at']);
        $dto->modifiedAt = new DateTime($category['modified_at']);
        
        return $dto->normalize($groups);
    }
}