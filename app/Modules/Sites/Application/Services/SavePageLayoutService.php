<?php

declare(strict_types=1);

namespace App\Modules\Sites\Application\Services;

use App\Exceptions\BusinessRuleException;
use App\Models\User;
use App\Modules\Sites\Infrastructure\Models\SitePage;
use App\Support\Tenancy\TenantContext;
use App\Support\Types\FeatureManager;

final readonly class SavePageLayoutService
{
    public function __construct(private BlockRegistry $registry, private FeatureManager $features) {}

    public function execute(User $user, SitePage $page, array $layout): void
    {
        abort_unless($page->site->event->tenant_id === app(TenantContext::class)->idFor($user), 403);
        foreach (['header', 'content', 'footer'] as $zone) {
            foreach ($layout[$zone] ?? [] as $block) {
                $definition = $this->registry->get((string) ($block['type'] ?? ''));
                if (($definition['premium'] ?? false) && ! $this->features->allows($user, $definition['feature'])) {
                    throw new BusinessRuleException('Este bloco exige o plano Premium.', 'PREMIUM_FEATURE_REQUIRED', 403);
                }
            }
        }
        $page->update(['header' => array_values($layout['header'] ?? []), 'content' => array_values($layout['content'] ?? []), 'footer' => array_values($layout['footer'] ?? [])]);
    }
}
