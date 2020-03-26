<?php
declare(strict_types=1);

namespace App\Entity;

use App\Controller\BaseApiController;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

/**
 * @ORM\Entity
 * @ORM\Table(name="category")
 * @ORM\HasLifecycleCallbacks()
 */
class Category implements JsonSerializable
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $name;

    /**
     * @ORM\Column(type="datetime", name="created_at")
     */
    private $createdAt;
    
    /**
     * @ORM\Column(type="datetime", name="modified_at")
     */
    private $modifiedAt;
    
    /**
     * Update dates during creation and modification
     */
    
    /**
     * Gets triggered only on insert
     * @ORM\PrePersist
     */
    public function onPrePersist(): void
    {
        $this->createdAt = new DateTime("now");
        $this->modifiedAt = new DateTime("now");
    }

    /**
     * Gets triggered every time on update
     * @ORM\PreUpdate
     */
    public function onPreUpdate(): void
    {
        $this->modifiedAt = new DateTime("now");
    }
    
    /** 
     * Getters and setters
     */

    /**
     * Get Category Id
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Set Category Id
     * @param int $id
     * @return Category
     */
    public function setId(int $id): Category
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get CreatedAt
     * @return DateTime
     */
    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }
 
    /**
     * Get ModifiedAt
     * @return DateTime
     */
    public function getModifiedAt(): DateTime
    {
        return $this->modifiedAt;
    }
    
    /**
     * Get Category Name
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Set Category Name
     * @param string $name
     * @return Category
     */
    public function setName($name): Category
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Serialization
     */
    
    /**
     * Specify data which should be serialized to JSON
     * @return array
     */    
    public function jsonSerialize(): array
    {
        return [
            "id"         => $this->getId(),
            "name"       => $this->getName(),
            "createdAt"  => $this->getCreatedAt()->format(BaseApiController::DEFAULT_RESPONSE_DATETIME_FORMAT),
            "modifiedAt" => $this->getModifiedAt()->format(BaseApiController::DEFAULT_RESPONSE_DATETIME_FORMAT)
        ];
    }
}
