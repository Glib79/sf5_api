<?php
declare(strict_types=1);

namespace App\Repository;

use App\DTO\BaseDto;
use App\DTO\CategoryDto;
use DateTime;
use Ramsey\Uuid\Uuid;

class CategoryRepository extends BaseRepository
{
    /**
     * Add new category to database
     * @param CategoryDto $category
     * @return bool
     */
    public function addCategory(CategoryDto $category): bool
    {
        $sql = 'INSERT INTO category (id, name, created_at, modified_at) 
            VALUES (:id, :name, :createdAt, :modifiedAt);';
    
        $stmt = $this->writeConn->prepare($sql);
        
        $now = new DateTime();
        
        $stmt->execute([
            'id'         => Uuid::uuid4()->toString(),
            'name'       => $category->name,
            'createdAt'  => $now->format(BaseDto::FORMAT_DATE_TIME_DB),
            'modifiedAt' => $now->format(BaseDto::FORMAT_DATE_TIME_DB)
        ]);
        
        return $stmt->errorCode() === '00000';
    }
    
    /**
     * Delete Category from database
     * @param string $id
     * @return bool
     */
    public function deleteCategory(string $id): bool
    {
        $sql = 'DELETE FROM category WHERE id = :id;';
        
        $stmt = $this->writeConn->prepare($sql);
        $stmt->execute([
            'id' => $id
        ]);
        
        return $stmt->errorCode() === '00000';
    }
    
    /**
     * Find list of categories
     * @return array
     */
    public function findCategories(): array
    {
        $sql = 'SELECT * FROM category;';
        
        $stmt = $this->readConn->prepare($sql);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
    
    /**
     * Get single category by id
     * @param string $id
     * @return array
     */
    public function getCategoryById(string $id): array
    {
        $sql = 'SELECT * FROM category WHERE id = :id;';
        
        $stmt = $this->readConn->prepare($sql);
        $stmt->execute(['id' => $id]);
        
        return $stmt->fetch() ?: [];
    }
     
    /**
     * Update category in database
     * @param CategoryDto $category
     * @return bool
     */
    public function updateCategory(CategoryDto $category): bool
    {
        $sql = 'UPDATE category SET name = :name, modified_at = :modifiedAt WHERE id = :id;';
        
        $stmt = $this->writeConn->prepare($sql);
        
        $now = new DateTime();
        
        $stmt->execute([
            'name'       => $category->name,
            'modifiedAt' => $now->format(BaseDto::FORMAT_DATE_TIME_DB),
            'id'         => $category->id->toString()
        ]);
        
        return $stmt->errorCode() === '00000';
    }
}

