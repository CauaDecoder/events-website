<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tenants', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('owner_user_id')->constrained('users')->cascadeOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->timestamps();
        });

        Schema::table('events', fn (Blueprint $table) => $table->foreignId('tenant_id')->nullable()->after('id')->constrained()->cascadeOnDelete());
        Schema::table('media_assets', fn (Blueprint $table) => $table->foreignId('tenant_id')->nullable()->after('id')->constrained()->cascadeOnDelete());
        Schema::table('team_members', fn (Blueprint $table) => $table->foreignId('tenant_id')->nullable()->after('id')->constrained()->cascadeOnDelete());

        foreach (DB::table('users')->orderBy('id')->get() as $user) {
            $tenantId = DB::table('tenants')->insertGetId([
                'owner_user_id' => $user->id,
                'name' => "Workspace de {$user->name}",
                'slug' => 'workspace-'.$user->id.'-'.Str::lower(Str::random(5)),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            DB::table('events')->where('user_id', $user->id)->update(['tenant_id' => $tenantId]);
            DB::table('media_assets')->where('user_id', $user->id)->update(['tenant_id' => $tenantId]);
            DB::table('team_members')->where('owner_user_id', $user->id)->update(['tenant_id' => $tenantId]);
        }

        Schema::create('domains', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('site_id')->nullable()->constrained()->nullOnDelete();
            $table->string('hostname')->unique();
            $table->string('status', 32)->default('pending');
            $table->string('verification_token', 64);
            $table->string('ssl_status', 32)->default('pending');
            $table->timestamp('verified_at')->nullable();
            $table->boolean('is_primary')->default(false);
            $table->timestamps();
            $table->index(['tenant_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('domains');
        Schema::table('team_members', fn (Blueprint $table) => $table->dropConstrainedForeignId('tenant_id'));
        Schema::table('media_assets', fn (Blueprint $table) => $table->dropConstrainedForeignId('tenant_id'));
        Schema::table('events', fn (Blueprint $table) => $table->dropConstrainedForeignId('tenant_id'));
        Schema::dropIfExists('tenants');
    }
};
