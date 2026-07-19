<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sites', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('event_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('status')->default('draft');
            $table->json('theme')->nullable();
            $table->json('settings')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });
        Schema::create('site_pages', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('site_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('slug');
            $table->boolean('is_home')->default(false);
            $table->unsignedInteger('sort_order')->default(0);
            $table->json('header')->nullable();
            $table->json('content')->nullable();
            $table->json('footer')->nullable();
            $table->timestamps();
            $table->unique(['site_id', 'slug']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('site_pages');
        Schema::dropIfExists('sites');
    }
};
