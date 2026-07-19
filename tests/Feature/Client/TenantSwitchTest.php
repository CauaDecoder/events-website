<?php

declare(strict_types=1);

namespace Tests\Feature\Client;

use App\Models\User;
use App\Modules\Tenancy\Infrastructure\Models\TeamMember;
use App\Support\Tenancy\TenantContext;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

final class TenantSwitchTest extends TestCase
{
    use RefreshDatabase;

    public function test_active_member_can_switch_to_shared_workspace(): void
    {
        $owner = User::factory()->create();
        $member = User::factory()->create();
        $tenant = app(TenantContext::class)->resolveFor($owner);
        TeamMember::query()->create(['tenant_id' => $tenant->id, 'owner_user_id' => $owner->id, 'user_id' => $member->id, 'email' => $member->email, 'role' => 'editor', 'status' => 'active', 'invitation_token' => Str::random(64), 'accepted_at' => now()]);

        $this->actingAs($member)->post(route('client.tenants.switch', $tenant))
            ->assertRedirect(route('client.dashboard'))
            ->assertSessionHas('tenant_id', $tenant->id);
    }

    public function test_user_cannot_switch_to_unrelated_workspace(): void
    {
        $owner = User::factory()->create();
        $unrelated = User::factory()->create();
        $tenant = app(TenantContext::class)->resolveFor($owner);

        $this->actingAs($unrelated)->post(route('client.tenants.switch', $tenant))->assertForbidden();
    }
}
