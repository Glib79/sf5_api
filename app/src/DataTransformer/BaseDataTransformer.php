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
}