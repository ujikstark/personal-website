<?php

declare(strict_types=1);

namespace Model\User;

use Symfony\Component\Validator\Constraints as Assert;


final class CreateUserDTO
{
    #[
        Assert\Email,
        Assert\NotBlank
    ]
    private string $email;

    #[
        Assert\Regex(pattern: '/^[a-zA-Z0-9]{3,}$/'),
        Assert\NotBlank
    ]
    private string $name;

    #[
        Assert\Length(min: 4),
        Assert\Regex(pattern: '/\d/')
    ]
    private string $password;

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

   
}
