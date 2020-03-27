<?php
declare(strict_types=1);

namespace App\DataTransformer;

use App\Support\ValidationException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class BaseDataTransformer
{
    /**
     * @var ValidatorInterface
     */
    protected $validator;
    
    /**
     * BaseDataTransformer constructor
     * @param ValidatorInterface $validator
     */
    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }
    
    /**
     * Validate Dto object
     * @param object $dto
     * @return bool
     */
    protected function validate(object $dto): bool
    {
        $validationErrors = $this->validator->validate($dto);
        
        if (count($validationErrors) > 0) {
            throw new ValidationException((string) $validationErrors);
        }
        
        return true;
    }
}