<?php
declare(strict_types=1);

namespace App\Service;

use App\DTO\CategoryDto;
use App\Repository\CategoryRepository;
use Exception;

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
     * @return string $id - created record id
     */
    public function createCategory(CategoryDto $dto): string
    {
        return $this->categoryRepository->addCategory($dto);
    }
    
    /**
     * Delete Category
     * @param string $id
     */
    public function deleteCategory(string $id): void
    {
        $this->categoryRepository->deleteCategory($id);
    }
    
    /**
     * Update Category
     * @param CategoryDto $dto
     */
    public function updateCategory(CategoryDto $dto): void
    {
        $this->categoryRepository->updateCategory($dto);
    }
}