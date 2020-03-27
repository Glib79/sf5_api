<?php
declare(strict_types=1);

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class UserDto
{
    /**
     * @var int
     */
    public $id;
    
    /**
     * @Assert\NotBlank()
     * @Assert\Email()
     * @var string
     */
    public $email;
    
    /**
     * @Assert\NotBlank()
     * @Assert\Regex(
     *     pattern="/\d/",
     *     message="Password not meet our standards"
     * )
     * @var string
     */
    public $password;
}
