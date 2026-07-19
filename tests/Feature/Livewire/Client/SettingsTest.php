<?php

declare(strict_types=1);

namespace Tests\Feature\Livewire\Client;

use App\Livewire\Client\Settings\Index;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use Tests\TestCase;

final class SettingsTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_update_profile_information(): void
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)->test(Index::class)
            ->set('profile.name', 'Marina Silva')
            ->call('updateProfile')
            ->assertHasNoErrors();

        $this->assertSame('Marina Silva', $user->fresh()->name);
    }

    public function test_user_can_change_password_with_current_password(): void
    {
        $user = User::factory()->create(['password' => 'current-password']);

        Livewire::actingAs($user)->test(Index::class)
            ->set('password.current_password', 'current-password')
            ->set('password.password', 'New-secure-password-123!')
            ->set('password.password_confirmation', 'New-secure-password-123!')
            ->call('updatePassword')
            ->assertHasNoErrors();

        $this->assertTrue(Hash::check('New-secure-password-123!', $user->fresh()->password));
    }

    public function test_wrong_current_password_is_rejected(): void
    {
        $user = User::factory()->create(['password' => 'current-password']);

        Livewire::actingAs($user)->test(Index::class)
            ->set('password.current_password', 'wrong-password')
            ->set('password.password', 'New-secure-password-123!')
            ->set('password.password_confirmation', 'New-secure-password-123!')
            ->call('updatePassword')
            ->assertHasErrors('password.current_password');
    }
}
