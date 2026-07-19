<?php

declare(strict_types=1);

namespace App\Livewire\Forms\Events;

use App\Modules\Events\Application\DTOs\CreateEventData;
use Carbon\Carbon;
use Livewire\Form;

final class CreateEventForm extends Form
{
    public string $name = '';

    public string $event_type = 'party';

    public string $event_date = '';

    public function rules(): array
    {
        return ['name' => ['required', 'string', 'max:255'], 'event_type' => ['required', 'in:party,birthday,wedding,corporate,other'], 'event_date' => ['nullable', 'date']];
    }

    public function toDto(): CreateEventData
    {
        return new CreateEventData($this->name, $this->event_type, $this->event_date !== '' ? Carbon::parse($this->event_date) : null);
    }
}
