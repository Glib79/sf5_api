<?php
declare(strict_types=1);

namespace App\Tests\Service;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use App\Service\CategoryManager;
use PHPUnit\Framework\TestCase;

class CategoryManagerTest extends TestCase
{
    /**
     * SCENARIO: receiving name
     * EXPECTED: create and save to database Category object
     */
    public function testCreateCategory()
    {
        $category = new Category();
        $category->setName('name_string');
        
        $categoryRepository = $this->createMock(CategoryRepository::class);
        
        $categoryRepository->expects($this->once())
            ->method('create')
            ->with($category)
            ->willReturn(true);
        
        $categoryManager = new CategoryManager($categoryRepository);
        
        $result = $categoryManager->createCategory('name_string');
        
        $this->assertTrue($result);
    }
    
    /**
     * SCENARIO: receiving Category object 
     * EXPECTED: remove received object from database
     */
    public function testDeleteCategory()
    {
        $category = new Category();
        $category->setName('name_string');
        
        $categoryRepository = $this->createMock(CategoryRepository::class);
        
        $categoryRepository->expects($this->once())
            ->method('delete')
            ->with($category)
            ->willReturn(true);
        
        $categoryManager = new CategoryManager($categoryRepository);
        
        $result = $categoryManager->deleteCategory($category);
        
        $this->assertTrue($result);
    }
    
    /**
     * SCENARIO: receiving Category object and new name
     * EXPECTED: update received object and save to database
     */
    public function testUpdateCategory()
    {
        $category = new Category();
        $category->setName('name_string');
        
        $categoryRepository = $this->createMock(CategoryRepository::class);
        
        $categoryRepository->expects($this->once())
            ->method('save')
            ->willReturn(true);
        
        $categoryManager = new CategoryManager($categoryRepository);
        
        $result = $categoryManager->updateCategory($category, 'new_name_string');
        
        $this->assertTrue($result);
        $this->assertSame('new_name_string', $category->getName());
    }
}