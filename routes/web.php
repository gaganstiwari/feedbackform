<?php

use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\FeedbackLinkController;

use Illuminate\Support\Facades\Route;


/*------------------------------------
| BLOG ROUTES
|------------------------------------*/

Route::get('/feedback', [FeedbackController::class, 'index'])
    ->name('feedback.form');

Route::post('/feedback/store', [FeedbackController::class, 'store'])
    ->name('feedback.store');

Route::get('/feedback-link', [FeedbackController::class, 'handleLink'])
    ->name('feedback.handle');

// Generate signed URL endpoint
Route::post('/feedback/generate-link', [FeedbackController::class, 'generateLink'])
    ->name('feedback.generate-link');
    
/*------------------------------------
| CUSTOMER ROUTES
|------------------------------------*/
Route::get('/customers', [CustomerController::class, 'index'])
    ->name('customers.index');
    
Route::get('/records-from-api', [CustomerController::class, 'getRecords'])
    ->name('customers.records');

Route::get('/records', [CustomerController::class, 'getRecords']);
Route::get('/generate-feedback', [FeedbackLinkController::class, 'generate']);
