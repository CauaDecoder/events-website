<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('team_members', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('owner_user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('email');
            $table->string('role', 32)->default('editor');
            $table->string('status', 32)->default('pending');
            $table->string('invitation_token', 64)->unique();
            $table->timestamp('accepted_at')->nullable();
            $table->timestamps();
            $table->unique(['owner_user_id', 'email']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('team_members');
    }
};
