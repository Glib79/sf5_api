<?php
declare(strict_types=1);

namespace App\Repository;

use App\DTO\BaseDto;
use App\DTO\CategoryDto;
use Doctrine\DBAL\Driver\Connection;

class CategoryRepository
{
    /**
     * @var Connection
     */
    private $connection;
    
    /**
     * CategoryRepository constructor
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }
    
    /**
     * Add new category to database
     * @param CategoryDto $category
     * @return bool
     */
    public function addCategory(CategoryDto $category): bool
    {
        $sql = 'INSERT INTO category (name, created_at, modified_at) VALUES (:name, :createdAt, :modifiedAt);';
    
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([
            'name'       => $category->name,
            'createdAt'  => $category->createdAt->format(BaseDto::FORMAT_DATE_TIME_DB),
            'modifiedAt' => $category->modifiedAt->format(BaseDto::FORMAT_DATE_TIME_DB)
        ]);
        
        return true;
    }
    
    /**
     * Delete Category from database
     * @param int $id
     * @return bool
     */
    public function deleteCategory(int $id): bool
    {
        $sql = 'DELETE FROM category WHERE id = :id;';
        
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([
            'id' => $id
        ]);
        
        return true;
    }
    
    /**
     * Find list of categories
     * @return array
     */
    public function findCategories(): array
    {
        $sql = 'SELECT * FROM category;';
        
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
    
    /**
     * Get single category by id
     * @param int $id
     * @return array
     */
    public function getCategoryById(int $id): array
    {
        $sql = 'SELECT * FROM category WHERE id = :id;';
        
        $stmt = $this->connection->prepare($sql);
        $stmt->execute(['id' => $id]);
        
        return $stmt->fetch();
    }
     
    /**
     * Update category in database
     * @param CategoryDto $category
     * @return bool
     */
    public function updateCategory(CategoryDto $category): bool
    {
        $sql = 'UPDATE category SET name = :name, modified_at = :modifiedAt WHERE id = :id;';
        
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([
            'name'       => $category->name,
            'modifiedAt' => $category->modifiedAt->format(BaseDto::FORMAT_DATE_TIME_DB),
            'id'         => $category->id
        ]);
        
        return true;
    }
}

