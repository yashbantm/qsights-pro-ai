<?php

namespace App\Services;

use App\Models\Activity;
use App\Models\Notification;
use Illuminate\Support\Facades\Mail;

class ActivityApprovalService
{
    public function sendApprovalRequest(Activity $activity): void
    {
        $token = $activity->generateApprovalToken();
        $approveUrl = url("/approve/{$token}?action=approve");
        $declineUrl = url("/approve/{$token}?action=decline");

        $notification = Notification::create([
            'recipient_email' => $activity->manager_email,
            'recipient_name' => $activity->manager_name,
            'type' => 'activity_approval_request',
            'subject' => 'Activity Approval Required: ' . $activity->activity_name,
            'body' => view('emails.activity-approval', [
                'activity' => $activity,
                'approveUrl' => $approveUrl,
                'declineUrl' => $declineUrl,
            ])->render(),
            'data' => [
                'activity_id' => $activity->id,
                'approve_url' => $approveUrl,
                'decline_url' => $declineUrl,
            ],
            'language_code' => 'en',
        ]);

        // Send email (placeholder - integrate SendGrid)
        // Mail::to($activity->manager_email)->send(new ActivityApprovalMail($activity, $approveUrl, $declineUrl));

        $notification->update(['status' => 'sent', 'sent_at' => now()]);
    }

    public function approve(Activity $activity, $approvedBy): void
    {
        $activity->update([
            'status' => 'approved',
            'approved_at' => now(),
            'approved_by' => $approvedBy->id,
        ]);

        // Generate participant links
        $activity->generateParticipantLinks();

        // Notify program admin
        $this->notifyProgramAdmin($activity, 'approved');
    }

    public function decline(Activity $activity, string $reason): void
    {
        $activity->update([
            'status' => 'draft',
            'decline_reason' => $reason,
        ]);

        // Notify program admin
        $this->notifyProgramAdmin($activity, 'declined', $reason);
    }

    private function notifyProgramAdmin(Activity $activity, string $status, ?string $reason = null): void
    {
        $programAdmin = $activity->program->programAccounts()
            ->where('role', 'admin')
            ->first();

        if ($programAdmin) {
            Notification::create([
                'user_id' => $programAdmin->user_id,
                'recipient_email' => $programAdmin->user->email,
                'recipient_name' => $programAdmin->user->name,
                'type' => $status === 'approved' ? 'activity_approved' : 'activity_declined',
                'subject' => "Activity {$status}: {$activity->activity_name}",
                'body' => $status === 'approved' 
                    ? "Your activity has been approved and is now live."
                    : "Your activity was declined. Reason: {$reason}",
                'data' => [
                    'activity_id' => $activity->id,
                    'reason' => $reason,
                ],
                'language_code' => 'en',
                'status' => 'pending',
            ]);
        }
    }
}
