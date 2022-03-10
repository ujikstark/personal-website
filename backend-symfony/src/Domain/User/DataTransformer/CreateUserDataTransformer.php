<?php

namespace Domain\User\DataTransformer;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Entity\User;
use Model\User\CreateUserDTO;


final class CreateUserDataTransformer implements DataTransformerInterface
{
    public function __construct(
        private ValidatorInterface $validator
    )
    {
    
    }

    /**
     * @param CreatedUserDTO $object
     * 
     * @throws ValidationException
     */
    public function transform($object, string $to, array $context = [])
    {
        $this->validator->validate($object);

        $user = new User();

        $user
            ->setPassword($object->getPassword())
            ->setName($object->getName())
            ->setEmail($object->getEmail());
        
        return $user;
    }



    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        return isset($context['input']['class']) && CreateUserDTO::class === $context['input']['class'];
    }
}