<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('responses', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('uuid_generate_v4()'));
            $table->foreignUuid('activity_id')->constrained()->onDelete('cascade');
            $table->foreignUuid('participant_id')->nullable()->constrained()->onDelete('set null');
            $table->string('participant_type')->default('general'); // general or guest
            $table->string('language_code', 10)->default('en');
            $table->json('answers'); // Complete response data
            $table->integer('completion_percentage')->default(0);
            $table->enum('status', ['draft', 'in_progress', 'completed'])->default('draft');
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('responses');
    }
};
