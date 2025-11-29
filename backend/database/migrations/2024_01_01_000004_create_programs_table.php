<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('programs', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('uuid_generate_v4()'));
            $table->foreignUuid('organization_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('logo')->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->boolean('multilingual')->default(false);
            $table->json('supported_languages')->nullable(); // ['en', 'es', 'fr']
            $table->string('default_language')->default('en');
            $table->json('theme')->nullable(); // {color: '#xxx', banner: 'url'}
            $table->boolean('auto_expire')->default(true);
            $table->enum('status', ['draft', 'active', 'expired', 'archived'])->default('draft');
            $table->timestamps();
            $table->softDeletes();
        });

        // Program accounts (auto-generated)
        Schema::create('program_accounts', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('uuid_generate_v4()'));
            $table->foreignUuid('program_id')->constrained()->onDelete('cascade');
            $table->foreignUuid('user_id')->constrained()->onDelete('cascade');
            $table->enum('role', ['admin', 'manager', 'moderator']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('program_accounts');
        Schema::dropIfExists('programs');
    }
};
