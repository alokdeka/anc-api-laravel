<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\Api;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public API Routes (No auth required, read-only)
|--------------------------------------------------------------------------
*/

Route::prefix('circulars')->group(function () {
    Route::get('/', [Api\CircularController::class, 'index']);
    Route::get('/{slug}', [Api\CircularController::class, 'show']);
});

Route::get('/programs',          [Api\ProgramController::class, 'index']);
Route::get('/institutes',        [Api\InstituteController::class, 'index']);
Route::get('/forms',             [Api\FormController::class, 'index']);
Route::get('/settings',          [Api\SettingsController::class, 'index']);
Route::get('/cne',               [Api\CneController::class, 'index']);
Route::get('/administration',    [Api\AdministrationController::class, 'index']);
Route::post('/contact',          [Api\ContactController::class, 'store']);

Route::prefix('examinations')->group(function () {
    Route::get('/timetable', [Api\ExaminationController::class, 'timetable']);
    Route::get('/results',   [Api\ExaminationController::class, 'results']);
});

Route::middleware('throttle:10,1')->group(function () {
    Route::post('/registrations/verify', [Api\RegistrationController::class, 'verify']);
});

Route::post('/grievance', [Api\GrievanceController::class, 'submit']);

/*
|--------------------------------------------------------------------------
| Admin Auth Routes
|--------------------------------------------------------------------------
*/

Route::prefix('auth')->group(function () {
    Route::post('/login',  [Admin\AuthController::class, 'login']);
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [Admin\AuthController::class, 'logout']);
        Route::get('/me',      [Admin\AuthController::class, 'me']);
    });
});

/*
|--------------------------------------------------------------------------
| Admin Protected Routes (Sanctum auth required)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth:sanctum'])->prefix('admin')->group(function () {

    // Dashboard
    Route::get('/dashboard', [Admin\DashboardController::class, 'index']);

    // Circulars & Messages
    Route::apiResource('circulars', Admin\CircularController::class);
    Route::get('contact-messages', [Admin\ContactMessageController::class, 'index']);
    Route::patch('contact-messages/{message}/read', [Admin\ContactMessageController::class, 'markAsRead']);
    Route::delete('contact-messages/{message}', [Admin\ContactMessageController::class, 'destroy']);

    // Institutes CRUD
    Route::apiResource('institutes', Admin\InstituteController::class);

    // Downloadable Forms CRUD
    Route::apiResource('forms', Admin\FormController::class);

    // Nurse Registrations
    Route::apiResource('registrations', Admin\RegistrationController::class);
    Route::post('/registrations/{nurse}/approve', [Admin\RegistrationController::class, 'approve']);
    Route::post('/registrations/{nurse}/reject',  [Admin\RegistrationController::class, 'reject']);
    Route::post('/registrations/{nurse}/revoke',  [Admin\RegistrationController::class, 'revoke']);

    // Users Management
    Route::apiResource('users', Admin\UserController::class)->except(['show']);

    // Settings
    Route::get('/settings',       [Admin\SettingsController::class, 'index']);
    Route::post('/settings',      [Admin\SettingsController::class, 'update']);
    
    // Media Library
    Route::get('/media',          [Admin\MediaController::class, 'index']);
    Route::post('/media',         [Admin\MediaController::class, 'store']);
    Route::delete('/media/{file}',[Admin\MediaController::class, 'destroy']);

    // CNE Content
    Route::get('/cne/{section}',  [Admin\CneContentController::class, 'show']);
    Route::put('/cne/{section}',  [Admin\CneContentController::class, 'update']);

    // Audit Log (read-only)
    Route::get('/audit-log',      [Admin\AuditLogController::class, 'index']);
});
