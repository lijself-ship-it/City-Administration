<?php

use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\ExploreApiController;
use App\Http\Controllers\Api\InitController;
use App\Http\Controllers\Api\InteractiveApiController;
use App\Http\Controllers\Api\MediaController;
use App\Http\Controllers\Api\PortalController;
use App\Http\Controllers\Api\SearchApiController;
use App\Http\Controllers\Api\ServiceApiController;
use App\Http\Controllers\Api\TransparencyApiController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes - V1 Public Portal & Administration
|--------------------------------------------------------------------------
*/

Route::prefix('v1')->group(function () {
    // 1. Unified Bootstrap Endpoint (Single HTTP round-trip)
    Route::get('/init', InitController::class);

    // 2. Portal & Structure
    Route::get('/hero-sliders', [PortalController::class, 'heroSliders']);
    Route::get('/emergency-contacts', [PortalController::class, 'emergencyContacts']);
    Route::get('/pages/{slug}', [PortalController::class, 'page']);
    Route::get('/government/members', [PortalController::class, 'governmentMembers']);
    Route::get('/government/departments', [PortalController::class, 'governmentDepartments']);

    // 3. Media Center (News, Events, Vacancies, Tenders)
    Route::get('/news', [MediaController::class, 'news']);
    Route::get('/news/{slug}', [MediaController::class, 'newsDetail']);
    Route::get('/events', [MediaController::class, 'events']);
    Route::get('/vacancies', [MediaController::class, 'vacancies']);
    Route::get('/tenders', [MediaController::class, 'tenders']);

    // 4. Citizen Services (Text & Step Guides)
    Route::get('/services', [ServiceApiController::class, 'index']);
    Route::get('/services/{slug}', [ServiceApiController::class, 'show']);

    // 5. Explore & Tourism
    Route::get('/explore', [ExploreApiController::class, 'index']);
    Route::get('/explore/{slug}', [ExploreApiController::class, 'show']);

    // 6. Transparency (Programs, Public Directives/PDFs, Galleries)
    Route::get('/programs', [TransparencyApiController::class, 'programs']);
    Route::get('/resources', [TransparencyApiController::class, 'resources']);
    Route::get('/galleries', [TransparencyApiController::class, 'galleries']);

    // 7. Interactive Citizen Participation
    Route::post('/polls/{id}/vote', [InteractiveApiController::class, 'votePoll'])->middleware('throttle:30,1');
    Route::get('/forum/topics', [InteractiveApiController::class, 'forumTopics']);
    Route::post('/forum/topics', [InteractiveApiController::class, 'createForumTopic'])->middleware('throttle:20,1');
    Route::post('/forum/topics/{id}/reply', [InteractiveApiController::class, 'replyForumTopic']);
    Route::get('/contact-info', [InteractiveApiController::class, 'contactInfo']);
    Route::post('/contact/submit', [InteractiveApiController::class, 'submitContact'])->middleware('throttle:20,1');
    Route::post('/newsletter/subscribe', [InteractiveApiController::class, 'subscribeNewsletter'])->middleware('throttle:20,1');

    // 8. Unified Global Search
    Route::get('/search', [SearchApiController::class, 'search']);

    // 9. Authentication
    Route::post('/auth/login', [AuthApiController::class, 'login'])->middleware('throttle:10,1');
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/auth/me', [AuthApiController::class, 'me']);
        Route::post('/auth/logout', [AuthApiController::class, 'logout']);
    });
});
