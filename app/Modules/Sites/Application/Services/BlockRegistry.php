<?php

declare(strict_types=1);

namespace App\Modules\Sites\Application\Services;

use App\Exceptions\BusinessRuleException;

final class BlockRegistry
{
    public function all(): array
    {
        return config('builder.blocks', []);
    }

    public function get(string $type): array
    {
        return $this->all()[$type] ?? throw new BusinessRuleException('Tipo de bloco inválido.', 'INVALID_BLOCK_TYPE');
    }
}
