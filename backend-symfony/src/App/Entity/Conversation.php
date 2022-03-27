<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ConversationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Model\Messaging\CreateConversationDTO;
use Symfony\Component\Serializer\Annotation as Serializer;
use Symfony\Component\Uid\Uuid;


#[ORM\Entity(repositoryClass: ConversationRepository::class)]
#[ApiResource(
    collectionOperations: [
        'get' => [
            'normalization_context' => [
                'groups' => Conversation::READ_COLLECTION_GROUP
            ]
        ],
        'post' => [
            'input' => CreateConversationDTO::class,
            'normalization_context' => [
                'groups' => Conversation::READ_ITEM_GROUP
            ]
        ]
    ],
    itemOperations: [
        'get'
    ],
    formats: ['json']
)]
class Conversation
{
    public const READ_COLLECTION_GROUP = 'conversation:read:collection';
    public const READ_ITEM_GROUP = 'conversation:read:item';

    #[ORM\Id]
    #[ORM\Column(type: 'uuid')]
    #[ApiProperty(identifier: true)]
    #[Serializer\Groups(groups: [
        Conversation::READ_COLLECTION_GROUP
    ])]
    private Uuid $id;
    
    #[ORM\OneToMany(mappedBy: 'conversation', cascade: ['PERSIST'], targetEntity: Participant::class, orphanRemoval: true)]
    #[Serializer\Groups(groups: [
        Conversation::READ_COLLECTION_GROUP
    ])]
    private Collection $participants;

    #[ORM\OneToMany(mappedBy: 'conversation', targetEntity: Message::class, orphanRemoval: true)]
    private Collection $messages; 

    public function __construct()
    {
        $this->participants = new ArrayCollection();
        $this->messages = new ArrayCollection();
        $this->id = Uuid::v4();
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function setId(Uuid $uuid): void 
    {
        $this->id = $uuid;
    } 

    /**
     * @return Collection<int, Participant>
     */
    public function getParticipants(): Collection
    {
        return $this->participants;
    }

    public function addParticipant(Participant $participant): self
    {
        if (!$this->participants->contains($participant)) {
            $this->participants[] = $participant;
            $participant->setConversation($this);
        }

        return $this;
    }

    public function removeParticipant(Participant $participant): self
    {
        $this->participants->removeElement($participant);

        return $this;
    }

    public function getParticipantByUser(User $user): ?Participant
    {
        foreach ($this->participants as $participant) {
            if ($participant->getUser() === $user) {
                return $participant;
            }
        }

        return null;
    }

    public function hasUser(User $user): bool
    {
        foreach ($this->participants as $participant) {
            if ($participant->getUser() === $user) {
                return true;
            }
        }

        return false;
    }

    /** @return Collection<int, Message> */
    public function getMessage(): Collection
    {
        return $this->messages;
    }

    public function addMessage(Message $message): self
    {
        if (!$this->messages->contains($message)) {
            $this->messages[] = $message;
            $message->setConversation($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): self
    {
        $this->messages->removeElement($message);

        return $this;
    }
}
