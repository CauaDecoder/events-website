<?php

declare(strict_types=1);

namespace App\Modules\Identity\Application\DTOs;

final readonly class CredentialsData
{
    public function __construct(
        public string $email,
        public string $password,
    ) {}
}
