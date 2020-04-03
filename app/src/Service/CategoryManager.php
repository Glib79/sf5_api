<?php
declare(strict_types=1);

namespace App\Service;

use DateTime;
use App\DTO\CategoryDto;
use App\Repository\CategoryRepository;

class CategoryManager
{
    /**
     * @var CategoryRepository
     */
    private $categoryRepository;
    
    /**
     * CategoryManager constructor
     * @param CategoryRepository $categoryRepository
     */
    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * Create category from dto
     * @param CategoryDto $dto
     * @return bool
     */
    public function createCategory(CategoryDto $dto): bool
    {
        $dto->createdAt = new DateTime();
        $dto->modifiedAt = new DateTime();
        
        return $this->categoryRepository->addCategory($dto);
    }
    
    /**
     * Delete Category
     * @param int $id
     * @return bool
     */
    public function deleteCategory(int $id): bool
    {
        return $this->categoryRepository->deleteCategory($id);
    }
    
    /**
     * Update Category
     * @param CategoryDto $dto
     * @return bool
     */
    public function updateCategory(CategoryDto $dto): bool
    {
        $dto->modifiedAt = new DateTime();
        
        return $this->categoryRepository->updateCategory($dto);
    }
}