<?php
declare(strict_types=1);

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class CategoryDto
{
    /**
     * @var int
     */
    public $id;
    
    /**
     * @Assert\NotBlank()
     * @Assert\Type(type="string")
     * @var string
     */
    public $name;
}
