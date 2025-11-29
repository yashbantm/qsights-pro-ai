<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('uuid_generate_v4()'));
            $table->foreignUuid('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('recipient_email');
            $table->string('recipient_name')->nullable();
            $table->enum('type', [
                'activity_invite',
                'activity_reminder',
                'activity_expiry',
                'activity_thank_you',
                'activity_approval_request',
                'activity_approved',
                'activity_declined',
                'program_created',
                'account_created'
            ]);
            $table->string('subject');
            $table->text('body');
            $table->json('data')->nullable();
            $table->string('language_code', 10)->default('en');
            $table->enum('status', ['pending', 'sent', 'failed'])->default('pending');
            $table->timestamp('sent_at')->nullable();
            $table->text('error_message')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
