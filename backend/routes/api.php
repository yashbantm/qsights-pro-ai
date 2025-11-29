<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\OrganizationController;

Route::prefix('v1')->group(function () {
    // Public routes
    Route::post('/auth/login', [AuthController::class, 'login']);
    Route::post('/auth/participant-login', [AuthController::class, 'participantLogin']);

    // Protected routes
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/auth/logout', [AuthController::class, 'logout']);
        Route::get('/auth/me', [AuthController::class, 'me']);

        // Organizations
        Route::apiResource('organizations', OrganizationController::class);
        Route::post('/organizations/upload-logo', [OrganizationController::class, 'uploadLogo']);

        // Programs
        // Route::apiResource('programs', ProgramController::class);

        // Activities
        // Route::apiResource('activities', ActivityController::class);

        // Questionnaires
        // Route::apiResource('questionnaires', QuestionnaireController::class);

        // Participants
        // Route::apiResource('participants', ParticipantController::class);
        // Route::post('/participants/bulk-upload', [ParticipantController::class, 'bulkUpload']);

        // Analytics
        // Route::get('/analytics/dashboard', [AnalyticsController::class, 'dashboard']);
    });

    // Activity approval (public with token)
    // Route::post('/activities/approve/{token}', [ActivityController::class, 'approve']);
    // Route::post('/activities/decline/{token}', [ActivityController::class, 'decline']);
});
