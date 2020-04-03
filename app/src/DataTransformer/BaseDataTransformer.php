<?php
declare(strict_types=1);

namespace App\DataTransformer;

use App\Support\ValidationException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class BaseDataTransformer
{
    /**
     * @var SerializerInterface
     */
    protected $serializer;
    
    /**
     * @var ValidatorInterface
     */
    protected $validator;
    
    /**
     * BaseDataTransformer constructor
     * @param SerializerInterface $serializer
     * @param ValidatorInterface $validator
     */
    public function __construct(SerializerInterface $serializer, ValidatorInterface $validator)
    {
        $this->serializer = $serializer;
        $this->validator = $validator;
    }
    
    /**
     * Validate Dto object
     * @param object $dto
     * @param array $groups
     * @return bool
     */
    protected function validate(object $dto, array $groups): bool
    {
        $validationErrors = $this->validator->validate($dto, null, $groups);
        
        if (count($validationErrors) > 0) {
            throw new ValidationException((string) $validationErrors);
        }
        
        return true;
    }
    
    /**
     * Serialize Dto object to JSON string
     * @param object $dto
     * @param array $groups
     * @return string
     */
    protected function serialize(object $dto, array $groups): string
    {
        return $this->serializer->serialize($dto, 'json', ['groups' => $groups]);
    }
}