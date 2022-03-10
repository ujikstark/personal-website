<?php

declare(strict_types=1);

namespace Domain\Todo\DataTransformer;

use ApiPlatform\Core\Bridge\Symfony\Validator\Exception\ValidationException;
use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use ApiPlatform\Core\Serializer\AbstractItemNormalizer;
use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Entity\Todo;
use App\Entity\User;
use Model\Todo\PersistTodoDTO;
use Symfony\Component\Security\Core\Security;

class PersistTodoDataTransformer implements DataTransformerInterface 
{
    private const UPDATE_VALIDATION_GROUP = 'update_todo';
    private const CREATE_VALIDATION_GROUP = 'create_todo';

    public function __construct(
        private Security $security,
        private ValidatorInterface $validator
    )
    {
        
    }

    /**
     * @param PersistTodoDTO $object
     * 
     * @throws ValidationException
     */
    
    public function transform($object, string $to, array $context = [])
    {
        if (isset($context['collection_operation_name'])) {
            /** @var User @user */
            $user = $this->security->getUser();
            $validationGroup = self::CREATE_VALIDATION_GROUP;

            $todo = new Todo();
            $todo->setUser($user);
        } else {
            $validationGroup = self::UPDATE_VALIDATION_GROUP;
            $todo = $context[AbstractItemNormalizer::OBJECT_TO_POPULATE];
        }

        $this->validator->validate($object, ['groups' => $validationGroup]);
        
        $todo
            ->setName($object->getName())
            ->setDate($object->getDate())
            ->setIsDone($object->isDone())
            ->setDescription($object->getDescription())
            ->setReminder($object->getReminder());

        return $todo;
    }

    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        return isset($context['input']['class']) && PersistTodoDTO::class === $context['input']['class'];
    }
}