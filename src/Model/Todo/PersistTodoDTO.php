<?php

declare(strict_types=1);

namespace Model\Todo;

use Symfony\Component\Validator\Constraints as Assert;

class PersistTodoDTO 
{
    #[
        Assert\NotBlank(groups: ['update_todo', 'create_todo']),
        Assert\Length(max: 50, groups: ['update_todo', 'create_todo'])
    ]
    private string $name = '';

    #[Assert\Length(max: 100, groups: ['update_todo', 'create_todo'])]
    private ?string $description = null;
    
    #[Assert\GreaterThanOrEqual(value: 'today', groups: ['create_todo'])]
    private ?\DateTime $date = null;
    
    #[Assert\isFalse(groups: ['create_todo'])]
    private bool $isDone = false;
    
    #[
        Assert\LessThan(propertyPath: 'date', groups: ['update_todo', 'create_todo']),
        Assert\GreaterThanOrEqual(value: 'today', groups: ['update_todo', 'create_todo'])
    ]
    private ?\DateTimeInterface $reminder = null;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDate(): ?\DateTime
    {
        return $this->date;
    }

    public function setDate(?\DateTime $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function isDone(): bool
    {
        return $this->isDone;
    }

    public function setIsDone(bool $isDone): self
    {
        $this->isDone = $isDone;

        return $this;
    }

    public function getReminder(): ?\DateTimeInterface
    {
        return $this->reminder;
    }

    public function setReminder(?\DateTimeInterface $reminder): void
    {
        $this->reminder = $reminder;
    }


}