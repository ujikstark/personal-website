<?php

declare(strict_types=1);

namespace Model\Account;

class UpdatePasswordDTO
{
    private string $currentPassword;

    private string $newPassword;

    private string $confirmPassword;

    public function getCurrentPassword(): string
    {
        return $this->currentPassword;
    }

    public function setCurrentPassword(string $currentPassword): UpdatePasswordDTO
    {
        $this->currentPassword = $currentPassword;

        return $this;
    }

    public function getNewPassword(): string
    {
        return $this->newPassword;
    }

    public function setNewPassword(string $newPassword): UpdatePasswordDTO
    {
        $this->newPassword = $newPassword;

        return $this;
    }

    public function getConfirmPassword(): string
    {
        return $this->confirmPassword;
    }

    public function setConfirmPassword(string $confirmPassword): UpdatePasswordDTO
    {
        $this->confirmPassword = $confirmPassword;
        
        return $this;
    }
}