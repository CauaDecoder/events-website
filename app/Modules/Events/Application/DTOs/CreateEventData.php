<?php

declare(strict_types=1);

namespace App\Modules\Events\Application\DTOs;

use DateTimeInterface;

final readonly class CreateEventData
{
    public function __construct(public string $name, public string $eventType = 'party', public ?DateTimeInterface $eventDate = null) {}
}
