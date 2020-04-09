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
     * @return string $id - created record id
     */
    public function addCategory(CategoryDto $category): string
    {
        $sql = 'INSERT INTO category (id, name, created_at, modified_at) 
            VALUES (:id, :name, :createdAt, :modifiedAt);';
        
        $now = new DateTime();
        $id = Uuid::uuid4()->toString();
        
        $stmt = $this->execute(
            $this->writeConn, 
            $sql,
            [
                'id'         => $id,
                'name'       => $category->name,
                'createdAt'  => $now->format(BaseDto::FORMAT_DATE_TIME_DB),
                'modifiedAt' => $now->format(BaseDto::FORMAT_DATE_TIME_DB)
            ]
        );
        
        return $id;
    }
    
    /**
     * Delete Category from database
     * @param string $id
     * @return bool
     */
    public function deleteCategory(string $id): void
    {
        $sql = 'DELETE FROM category WHERE id = :id;';
        
        $stmt = $this->execute($this->writeConn, $sql, ['id' => $id]);
    }
    
    /**
     * Find list of categories
     * @return array
     */
    public function findCategories(): array
    {
        $sql = 'SELECT * FROM category;';
        
        $stmt = $this->execute($this->readConn, $sql);
        
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
        
        $stmt = $this->execute($this->readConn, $sql, ['id' => $id]);
        
        return $stmt->fetch() ?: [];
    }
     
    /**
     * Update category in database
     * @param CategoryDto $category
     */
    public function updateCategory(CategoryDto $category): void
    {
        $sql = 'UPDATE category SET name = :name, modified_at = :modifiedAt WHERE id = :id;';
        
        $now = new DateTime();
        
        $stmt = $this->execute(
            $this->writeConn, 
            $sql, 
            [
                'name'       => $category->name,
                'modifiedAt' => $now->format(BaseDto::FORMAT_DATE_TIME_DB),
                'id'         => $category->id->toString()
            ]
        );
    }
}

