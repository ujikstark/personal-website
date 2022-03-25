<?php

namespace App\Entity;

use App\Repository\MessageRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;


#[ORM\Entity(repositoryClass: MessageRepository::class)]

class Message
{
    public const CREATE_GROUP = 'message:create';

    #[ORM\Id]
    #[ORM\Column(type: 'uuid')]
    private $id;

    #[ORM\ManyToOne(targetEntity: Participant::class, inversedBy: 'messages')]
    #[ORM\JoinColumn(nullable: false)]
    private Participant $sender;

    #[ORM\ManyToOne(targetEntity: Conversation::class, inversedBy: 'messages')]
    #[ORM\JoinColumn(nullable: false)]
    private Conversation $conversation;
    
    #[ORM\Column(type: 'text')]
    private string $content = '';

    #[ORM\Column]
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
