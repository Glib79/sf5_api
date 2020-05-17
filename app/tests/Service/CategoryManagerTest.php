<?php
declare(strict_types=1);

namespace App\Tests\Service;

use App\DTO\CategoryDto;
use App\Repository\CategoryRepository;
use App\Service\CategoryManager;
use App\Tests\BaseTestCase;
use DateTime;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CategoryManagerTest extends BaseTestCase
{
    /**
     * SCENARIO: receiving name
     * EXPECTED: create and save to database Category object
     */
    public function testCreateCategory()
    {
        $serializer = $this->createMock(SerializerInterface::class);
        $validator = $this->createMock(ValidatorInterface::class);
        
        $categoryDto = new CategoryDto($serializer, $validator);
        $categoryDto->name = 'name_string';
        
        $categoryRepository = $this->createMock(CategoryRepository::class);
        
        $categoryRepository->expects($this->once())
            ->method('addCategory')
            ->with($categoryDto)
            ->willReturn(true);
        
        $categoryManager = new CategoryManager($categoryRepository);
        
        $result = $categoryManager->createCategory($categoryDto);
        
        $this->assertIsString($result);
    }
    
    /**
     * SCENARIO: receiving Category object 
     * EXPECTED: remove received object from database
     */
    public function testDeleteCategory()
    {
        $categoryRepository = $this->createMock(CategoryRepository::class);
        
        $categoryRepository->expects($this->once())
            ->method('deleteCategory')
            ->with('uuid_string')
            ->willReturn(true);
        
        $categoryManager = new CategoryManager($categoryRepository);
        
        $categoryManager->deleteCategory('uuid_string');
    }
    
    /**
     * SCENARIO: receiving Category object and new name
     * EXPECTED: update received object and save to database
     */
    public function testUpdateCategory()
    {
        $serializer = $this->createMock(SerializerInterface::class);
        $validator = $this->createMock(ValidatorInterface::class);
        
        $categoryDto = new CategoryDto($serializer, $validator);
        $categoryDto->id = 'uuid_string';
        $categoryDto->name = 'new_name_string';
        
        $categoryRepository = $this->createMock(CategoryRepository::class);
        
        $categoryRepository->expects($this->once())
            ->method('updateCategory')
            ->with($categoryDto)
            ->willReturn(true);
            
        $categoryManager = new CategoryManager($categoryRepository);
        
        $categoryManager->updateCategory($categoryDto);
    }
}
