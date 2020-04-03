<?php
declare(strict_types=1);

namespace App\DTO;

use DateTime;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

class UserDto extends BaseDto
{
    /**
     * @Groups({BaseDto::GROUP_SINGLE, BaseDto::GROUP_LIST})
     * @var int
     */
    public $id;
    
    /**
     * @Groups({BaseDto::GROUP_CREATE, BaseDto::GROUP_SINGLE, BaseDto::GROUP_LIST})
     * @Assert\NotBlank(groups={BaseDto::GROUP_CREATE})
     * @Assert\Email(groups={BaseDto::GROUP_CREATE})
     * @var string
     */
    public $email;
    
    /**
     * @Groups({BaseDto::GROUP_CREATE, BaseDto::GROUP_UPDATE})
     * @Assert\NotBlank(groups={BaseDto::GROUP_CREATE, BaseDto::GROUP_UPDATE})
     * @Assert\Regex(
     *     pattern="/\d/",
     *     message="Password not meet our standards",
     *     groups={BaseDto::GROUP_CREATE, BaseDto::GROUP_UPDATE}
     * )
     * @var string
     */
    public $password;
    
    /**
     * @Groups({BaseDto::GROUP_SINGLE})
     * @var DateTime
     */
    public $createdAt;
    
    /**
     * @Groups({BaseDto::GROUP_SINGLE})
     * @var DateTime
     */
    public $modifiedAt;
}
