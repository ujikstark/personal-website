<?php

namespace App\Entity;

use ApiPlatform\Core\Action\NotFoundAction;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\MessageRepository;
use Doctrine\ORM\Mapping as ORM;
use Model\Messaging\CreateMessageDTO;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Serializer\Annotation as Serializer;


#[ORM\Entity(repositoryClass: MessageRepository::class)]
#[ApiResource(
    collectionOperations: [
        'post' => [
            'input' => CreateMessageDTO::class,
            'normalization_context' => [
                'groups' => Message::CREATE_GROUP
            ],
        ],
    ],
    itemOperations: [
        'get' => [
            'controller' => NotFoundAction::class,
            'read' => false,
            'output' => false,
        ],
    ],
    formats: ['json']
)]
class Message
{
    public const CREATE_GROUP = 'message:create';

    #[ORM\Id]
    #[ORM\Column(type: 'uuid')]
    #[Serializer\Groups(groups: [
        Conversation::READ_COLLECTION_GROUP,
        Conversation::READ_ITEM_GROUP,
        Message::CREATE_GROUP,
    ])]
    private $id;

    #[ORM\ManyToOne(targetEntity: Participant::class, inversedBy: 'messages')]
    #[ORM\JoinColumn(nullable: false)]
    #[Serializer\Groups(groups: [
        Conversation::READ_COLLECTION_GROUP,
        Conversation::READ_ITEM_GROUP,
        Message::CREATE_GROUP,
    ])]
    private Participant $sender;

    #[ORM\ManyToOne(targetEntity: Conversation::class, inversedBy: 'messages')]
    #[ORM\JoinColumn(nullable: false)]
    #[Serializer\Groups(groups: [
        Message::CREATE_GROUP,
    ])]
    private Conversation $conversation;
    
    #[ORM\Column(type: 'text')]
    #[Serializer\Groups(groups: [
        Conversation::READ_COLLECTION_GROUP,
        Conversation::READ_ITEM_GROUP,
        Message::CREATE_GROUP,
    ])]
    private string $content = '';

    #[ORM\Column]
    #[Serializer\Groups(groups: [
        Conversation::READ_COLLECTION_GROUP,
        Conversation::READ_ITEM_GROUP,
        Message::CREATE_GROUP,
    ])]
    private \DateTimeImmutable $date;

    public function __construct()
    {
        $this->id = Uuid::v4();
        $this->date = new \DateTimeImmutable();
    } 

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getSender(): Participant
    {
        return $this->sender;
    }

    public function setSender(Participant $sender): self
    {
        $this->sender = $sender;

        return $this;
    }

    public function getConversation(): Conversation
    {
        return $this->conversation;
    }

    public function setConversation(Conversation $conversation): self
    {
        $this->conversation = $conversation;

        return $this;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getDate(): \DateTimeImmutable
    {
        return $this->date;
    }

    public function setDate(\DateTimeImmutable $date): self
    {
        $this->date = $date;

        return $this;
    }
}
