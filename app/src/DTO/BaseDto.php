<?php
declare(strict_types=1);

namespace App\DTO;

abstract class BaseDto
{
    //input
    public const GROUP_CREATE = 'create';
    public const GROUP_UPDATE = 'update';
    //output
    public const GROUP_LIST = 'list';
    public const GROUP_SINGLE = 'single';
}