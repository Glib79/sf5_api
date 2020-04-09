<?php
declare(strict_types=1);

namespace App\Support\Error;

use Exception;

/**
 * Exception returned when validation failed
 */
class ValidationException extends Exception
{
    /**
     * @var array
     */
    private $errors;
    
    /**
     * ValidationException constructor
     * @param string $message
     * @param array $errors
     */
    public function __construct(string $message, array $errors = [])
    {
        parent::__construct($message);
        
        $this->errors = $errors;
    }
    
    /**
     * Errors getter
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}
