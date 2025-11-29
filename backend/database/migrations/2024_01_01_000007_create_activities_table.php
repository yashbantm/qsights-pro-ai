<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activities', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('uuid_generate_v4()'));
            $table->foreignUuid('program_id')->constrained()->onDelete('cascade');
            $table->foreignUuid('questionnaire_id')->constrained()->onDelete('cascade');
            
            // Basic Info (S1)
            $table->enum('activity_type', ['survey', 'poll', 'assessment']);
            $table->string('activity_name');
            $table->text('about')->nullable();
            $table->string('survey_url')->nullable();
            $table->string('sender_email');
            $table->date('start_date');
            $table->date('end_date');
            
            // Manager Info (S1)
            $table->string('manager_name');
            $table->string('manager_email');
            
            // Pricing (S2)
            $table->string('project_code')->nullable();
            $table->decimal('configuration_price', 10, 2)->nullable();
            $table->decimal('subscription_price', 10, 2)->nullable();
            $table->decimal('tax_percentage', 5, 2)->default(0);
            
            // Configuration (S2)
            $table->integer('randomize_questions_count')->nullable();
            $table->integer('questions_per_page')->default(1);
            $table->integer('retake_count')->default(0);
            
            // Branding (S3)
            $table->string('logo_main')->nullable();
            $table->json('additional_logos')->nullable();
            $table->text('header_html')->nullable();
            $table->text('footer_html')->nullable();
            
            // Legal (S3)
            $table->text('terms_and_conditions')->nullable();
            $table->text('privacy_policy')->nullable();
            $table->text('disclaimer')->nullable();
            
            // Additional Services (S3)
            $table->json('additional_services')->nullable();
            
            // Lifecycle Management
            $table->enum('status', ['draft', 'pending_approval', 'approved', 'live', 'expired', 'closed'])->default('draft');
            $table->string('approval_token')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->foreignUuid('approved_by')->nullable()->constrained('users');
            $table->text('decline_reason')->nullable();
            
            // Links
            $table->string('general_link')->nullable();
            $table->string('guest_link')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};
