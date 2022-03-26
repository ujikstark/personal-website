<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Controller\Account\GetMeController;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Model\User\CreateUserDTO;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation as Serializer;
use Symfony\Component\Uid\Uuid;

#[
    ORM\Entity(repositoryClass: UserRepository::class),
    ORM\Table(name: '`user`'),
    ApiResource(
        // normalizationContext: ['groups' => ['get_users']],
   
        collectionOperations: [
            'get' => [
                'normalization_context' => [
                    'groups' => ['get_users']
                ],
            ],
            'post' => [
                'input' => CreateUserDTO::class,
                'normalization_context' => [
                    'groups' => ['get_user']
                ]
            ],
        ],
        itemOperations: [
            'get',
            'delete',
            'getMe' => [
                'method' => Request::METHOD_GET,
                'normalization_context' => [
                    'groups' => ['get_me'],
                ],
                'path' => GetMeController::PATH,
                'identifiers' => [],
                'controller' => GetMeController::class,
                'read' => false,
                'openapi_context' => [
                    'tags' => ['Account'],
                    'summary' => 'Retrieves current user resourcess.',
                    'description' => 'Retrieves current user resource.',
                    'parameters' => [],
                    'responses' => [
                        Response::HTTP_UNAUTHORIZED => [
                            'description' => 'Unauthenticated user',
                            'content' => [
                                'application/json' => [],
                            ],
                        ],
                    ]
                ]
            ]
        ],
        formats: ['json']
    )
]

class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[
        ORM\Id,
        ORM\Column(type: 'uuid'),
        Serializer\Groups(groups: [
            'get_users',
            'get_user',
            'get_me',
            Conversation::READ_COLLECTION_GROUP,
            Conversation::READ_ITEM_GROUP
        ])
    ]
    private Uuid $id;

    #[
        ORM\Column(type: 'datetime'),
        Serializer\Groups(groups: ['get_me', 'get_user'])    
    ]
    private \DateTimeInterface $createdAt;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $updatedAt = null;

    #[
        ORM\Column(type: 'string', length: 100),
        Serializer\Groups(groups: ['get_me', 'get_user'])
    ]
    private $name;

    #[
        ORM\Column(type: 'string', length: 180, unique: true),
        Serializer\Groups(groups: ['get_me'])
    ]
    private $email;

    #[ORM\Column(type: 'json')]
    private $roles = [];

    #[ORM\Column(type: 'string')]
    private $password;
        
    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Todo::class, orphanRemoval: true)]
    private Collection $todos;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $resetPasswordToken = null;
    
    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $resetPasswordExpirationDate = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Participant::class, orphanRemoval: true)]
    private Collection $participants;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->id = Uuid::v4();
        $this->todos = new ArrayCollection();
        $this->participants = new ArrayCollection();
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    //For tests purposes
     public function setId(Uuid $id): void
    {
        $this->id = $id;
    }

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdateAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdateAt(?\DateTimeInterface $updateAt): self
    {
        $this->updatedAt = $updateAt;

        return $this;
    }

    public function hasBeenUpdated(): void
    {
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        
        return $this;
    }


    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

     /**
     * @return Collection<int, Todo>
     */
    public function getTodos(): Collection
    {
        return $this->todos;
    }

    public function addTodo(Todo $todo): self
    {
        if (!$this->todos->contains($todo)) {
            $this->todos[] = $todo;
            $todo->setUser($this);
        }

        return $this;
    }

    public function removeTodo(Todo $todo): self
    {
        if ($this->todos->contains($todo)) {
            $this->todos->removeElement($todo);
        }

        return $this;
    }

    public function getResetPasswordToken(): ?string
    {
        return $this->resetPasswordToken;
    }

    public function setResetPasswordToken(?string $resetPasswordToken): void
    {
        $this->resetPasswordToken = $resetPasswordToken;
    }

    public function getResetPasswordExpirationDate(): ?\DateTimeInterface
    {
        return $this->resetPasswordExpirationDate;
    }

    public function setResetPasswordExpirationDate(?\DateTimeInterface $resetPasswordExpirationDate): void
    {
        $this->resetPasswordExpirationDate = $resetPasswordExpirationDate;
    }

    public function isResetPasswordTokenExpired(): bool
    {
        return null !== $this->resetPasswordExpirationDate && $this->resetPasswordExpirationDate < new \DateTime();
    }

    public function eraseResetPasswordData(): void
    {
        $this->resetPasswordExpirationDate = null;
        $this->resetPasswordToken = null;
    }

    /** @return Collection<int, Participant> */
    public function getParticipants(): Collection
    {
        return $this->participants;
    }
}
