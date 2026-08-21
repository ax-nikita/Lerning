<?php

declare(strict_types=1);

namespace App\User;

final readonly class Email
{
    private string $email;

    public function __construct(string $email)
    {
        $email = trim($email);

        if (
            strlen($email) > 255 ||
            strlen($email) < 1 ||
            filter_var($email, FILTER_VALIDATE_EMAIL) === false
        ) {
            throw new InvalidEmailException('Invalid email');
        }

        $this->email = $email;
    }

    public function value(): string
    {
        return $this->email;
    }
}
