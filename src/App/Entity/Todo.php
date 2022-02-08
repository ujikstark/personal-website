<?php

namespace App\Entity;

use ApiPlatform\Core\Action\NotFoundAction;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\TodoRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Serializer\Annotation as Serializer;


#[
    ORM\Entity(repositoryClass: TodoRepository::class),
    ApiResource(
        collectionOperations: [
            'get' => [
                'normalization_context' => [
                    'groups' => ['get_todos'],
                ],
            ],
            'post',
        ],
        itemOperations: [
            'delete',
            'put',
            'get' => [
                'controller' => NotFoundAction::class,
                'read' => false,
                'output' => false,
            ],
        ],
        formats: ['json']
    )
]
class Todo
{
    #[
        ORM\Id,
        ORM\Column(type: 'uuid'),
        Serializer\Groups(groups: ['get_todos', 'persist_todo'])
    ]
    private Uuid $id;
    
    #[
        ORM\Column(type: 'string', length: 255),
        Serializer\Groups(groups: ['get_todos', 'persist_todo'])        
    ]
    private string $name = '';

    #[
        ORM\Column(type: 'string', length: 255, nullable: true),
        Serializer\Groups(groups: ['get_todos', 'persist_todo'])        
    ]
    private ?string $description = null;

    #[
        ORM\Column(type: 'datetime', nullable: true),
        Serializer\Groups(groups: ['get_todos', 'persist_todo'])        
    ]
    private ?\DateTime $date = null;

    #[
        ORM\Column(type: 'boolean'),
        Serializer\Groups(groups: ['get_todos', 'persist_todo'])
    ]
    private bool $isDone = false;

    #[
        ORM\ManyToOne(targetEntity: User::class, inversedBy: 'todos'),
        ORM\JoinColumn(nullable: false)
    ]
    private User $user;

    #[
        ORM\Column(type: 'datetime', nullable: true),
        Serializer\Groups(groups: ['get_todos', 'persist_todo'])
    ]
    private ?\DateTimeInterface $reminder = null;

    public function __construct()
    {
        $this->id = Uuid::v4();
    }


    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): Todo
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): Todo
    {
        $this->description = $description;

        return $this;
    }

    public function getDate(): ?\DateTime
    {
        return $this->date;
    }

    public function setDate(?\DateTime $date): Todo
    {
        $this->date = $date;

        return $this;
    }

    public function getIsDone(): bool
    {
        return $this->isDone;
    }

    public function setIsDone(bool $isDone): Todo
    {
        $this->isDone = $isDone;

        return $this;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): Todo
    {
        $this->user = $user;

        return $this;
    }

    public function getReminder(): ?\DateTimeInterface
    {
        return $this->reminder;
    }

    public function setReminder(?\DateTimeInterface $reminder): self
    {
        $this->reminder = $reminder;

        return $this;
    }
}
