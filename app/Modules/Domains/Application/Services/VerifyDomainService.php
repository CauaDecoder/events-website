<?php

declare(strict_types=1);

namespace App\Modules\Domains\Application\Services;

use App\Modules\Domains\Infrastructure\Models\Domain;

final class VerifyDomainService
{
    public function execute(Domain $domain): bool
    {
        $verificationHost = config('domains.verification_prefix').'.'.$domain->hostname;
        $records = @dns_get_record($verificationHost, DNS_TXT) ?: [];
        $verified = collect($records)->contains(fn (array $record) => ($record['txt'] ?? null) === $domain->verification_token);

        if ($verified) {
            $domain->update(['status' => 'verified', 'verified_at' => now(), 'ssl_status' => 'provisioning']);
        }

        return $verified;
    }
}
