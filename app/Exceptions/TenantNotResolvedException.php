<?php

declare(strict_types=1);

namespace App\Exceptions;

final class TenantNotResolvedException extends BusinessRuleException
{
    public function __construct()
    {
        parent::__construct(
            message: 'Não foi possível determinar o tenant da requisição.',
            errorCode: 'TENANT_NOT_RESOLVED',
            status: 400,
        );
    }
}
