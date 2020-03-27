<?php
declare(strict_types=1);

namespace App\Service;

use App\DTO\CategoryDto;
use App\Entity\Category;
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
        $category = new Category();
        $category->setName($dto->name);
        
        return $this->categoryRepository->create($category);
    }
    
    /**
     * Delete Category
     * @param Category $category
     * @return bool
     */
    public function deleteCategory(Category $category): bool
    {
        return $this->categoryRepository->delete($category);
    }
    
    /**
     * Update Category
     * @param Category $category
     * @param CategoryDto $dto
     * @return bool
     */
    public function updateCategory(Category $category, CategoryDto $dto): bool
    {
        $category->setName($dto->name);
        
        return $this->categoryRepository->save();
    }
}