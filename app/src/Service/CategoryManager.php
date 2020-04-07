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
     */
    public function createCategory(CategoryDto $dto): void
    {
        if (!$this->categoryRepository->addCategory($dto)) {
            throw new Exception('Database error!');
        }
    }
    
    /**
     * Delete Category
     * @param string $id
     */
    public function deleteCategory(string $id): void
    {
        if (!$this->categoryRepository->deleteCategory($id)) {
            throw new Exception('Database error!');
        }
    }
    
    /**
     * Update Category
     * @param CategoryDto $dto
     */
    public function updateCategory(CategoryDto $dto): void
    {
        if (!$this->categoryRepository->updateCategory($dto)) {
            throw new Exception('Database error!');
        }
    }
}