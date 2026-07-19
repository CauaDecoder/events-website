<?php

declare(strict_types=1);

namespace App\Modules\Domains\Infrastructure\Models;

use App\Modules\Sites\Infrastructure\Models\Site;
use App\Modules\Tenancy\Infrastructure\Models\Tenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class Domain extends Model
{
    protected $fillable = ['tenant_id', 'site_id', 'hostname', 'status', 'verification_token', 'ssl_status', 'verified_at', 'is_primary'];

    protected function casts(): array
    {
        return ['verified_at' => 'datetime', 'is_primary' => 'boolean'];
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class);
    }
}
