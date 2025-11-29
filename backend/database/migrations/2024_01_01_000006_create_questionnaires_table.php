<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('questionnaires', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('uuid_generate_v4()'));
            $table->foreignUuid('program_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->json('sections')->nullable(); // Array of sections
            $table->json('questions'); // Complete question structure
            $table->json('conditional_logic')->nullable();
            $table->boolean('is_template')->default(false);
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $table->timestamps();
            $table->softDeletes();
        });

        // Multilingual content for questionnaires
        Schema::create('questionnaire_translations', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('uuid_generate_v4()'));
            $table->foreignUuid('questionnaire_id')->constrained()->onDelete('cascade');
            $table->string('language_code', 10);
            $table->string('title');
            $table->text('description')->nullable();
            $table->json('questions'); // Translated questions
            $table->timestamps();
            
            $table->unique(['questionnaire_id', 'language_code']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('questionnaire_translations');
        Schema::dropIfExists('questionnaires');
    }
};
