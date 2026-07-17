<?php

declare(strict_types=1);

namespace Tests\Feature\Livewire\Auth;

use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Models\User;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Livewire\Livewire;
use Tests\TestCase;

final class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register(): void
    {
        Notification::fake();

        Livewire::test(Register::class)
            ->set('form.name', 'Maria Silva')
            ->set('form.email', 'maria@example.com')
            ->set('form.password', 'password')
            ->set('form.password_confirmation', 'password')
            ->call('register')
            ->assertHasNoErrors()
            ->assertRedirect(route('verification.notice'));

        $this->assertAuthenticated();
        $user = User::query()->where('email', 'maria@example.com')->firstOrFail();
        Notification::assertSentTo($user, VerifyEmail::class);
    }

    public function test_user_can_login(): void
    {
        $user = User::factory()->create([
            'email' => 'user@example.com',
            'password' => 'password',
        ]);

        Livewire::test(Login::class)
            ->set('form.email', $user->email)
            ->set('form.password', 'password')
            ->call('authenticate')
            ->assertHasNoErrors()
            ->assertRedirect(route('client.dashboard'));

        $this->assertAuthenticatedAs($user);
    }

    public function test_invalid_password_is_rejected(): void
    {
        User::factory()->create(['email' => 'user@example.com']);

        Livewire::test(Login::class)
            ->set('form.email', 'user@example.com')
            ->set('form.password', 'invalid-password')
            ->call('authenticate')
            ->assertHasErrors('email');

        $this->assertGuest();
    }
}
