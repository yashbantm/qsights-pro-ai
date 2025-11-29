<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('participants', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('uuid_generate_v4()'));
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->string('avatar')->nullable();
            $table->enum('type', ['general', 'guest'])->default('general');
            $table->string('preferred_language')->default('en');
            $table->json('custom_fields')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
            $table->softDeletes();
        });

        // Participant-Program association (many-to-many)
        Schema::create('participant_program', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('uuid_generate_v4()'));
            $table->foreignUuid('participant_id')->constrained()->onDelete('cascade');
            $table->foreignUuid('program_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            
            $table->unique(['participant_id', 'program_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('participant_program');
        Schema::dropIfExists('participants');
    }
};
