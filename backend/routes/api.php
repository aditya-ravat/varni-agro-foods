<?php

use App\Http\Controllers\Api\Admin\ChamberController as AdminChamberController;
use App\Http\Controllers\Api\Admin\CommodityController as AdminCommodityController;
use App\Http\Controllers\Api\Admin\FacilityController as AdminFacilityController;
use App\Http\Controllers\Api\Admin\LeadController as AdminLeadController;
use App\Http\Controllers\Api\Admin\TariffController as AdminTariffController;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Public\CommodityController;
use App\Http\Controllers\Api\Public\FacilityController;
use App\Http\Controllers\Api\Public\LeadController;
use App\Http\Controllers\Api\Public\MetaController;
use App\Http\Controllers\Api\Public\PostController;
use App\Http\Controllers\Api\Public\ServiceController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    // Public, no auth
    Route::get('facilities', [FacilityController::class, 'index']);
    Route::get('facilities/{slug}', [FacilityController::class, 'show']);

    Route::get('commodities', [CommodityController::class, 'index']);
    Route::get('commodities/{slug}', [CommodityController::class, 'show']);

    Route::get('services', [ServiceController::class, 'index']);
    Route::get('services/{slug}', [ServiceController::class, 'show']);

    Route::get('posts', [PostController::class, 'index']);
    Route::get('posts/{slug}', [PostController::class, 'show']);

    Route::get('faqs/{group?}', [MetaController::class, 'faqs']);
    Route::get('testimonials', [MetaController::class, 'testimonials']);
    Route::get('banners/{placement}', [MetaController::class, 'banners']);
    Route::get('pages/{slug}', [MetaController::class, 'page']);
    Route::get('sitemap-data', [MetaController::class, 'sitemap']);

    Route::post('quote', [LeadController::class, 'quote'])->middleware('throttle:10,1');
    Route::post('contact', [LeadController::class, 'contact'])->middleware('throttle:10,1');
    Route::post('subscribe', [LeadController::class, 'subscribe'])->middleware('throttle:10,1');

    // Auth
    Route::post('auth/register', [AuthController::class, 'register'])->middleware('throttle:5,1');
    Route::post('auth/login', [AuthController::class, 'login'])->middleware('throttle:10,1');

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('auth/me', [AuthController::class, 'me']);
        Route::post('auth/logout', [AuthController::class, 'logout']);

        // ----- Admin -----
        Route::prefix('admin')->middleware('role:super-admin|branch-manager|operations-supervisor|gate-clerk|accounts|cms-editor|maintenance')->group(function () {
            Route::apiResource('facilities', AdminFacilityController::class);
            Route::apiResource('chambers', AdminChamberController::class);
            Route::apiResource('commodities', AdminCommodityController::class);
            Route::apiResource('tariffs', AdminTariffController::class);
            Route::apiResource('leads', AdminLeadController::class)->only(['index', 'show', 'update', 'destroy']);
        });

        // ----- Client portal -----
        Route::prefix('portal')->middleware('role:client')->group(function () {
            Route::get('dashboard', function () {
                return ['message' => 'Welcome to your Varni client portal'];
            });
            // Lots, bookings, invoices, gate passes — to be implemented Phase 4
        });
    });
});
