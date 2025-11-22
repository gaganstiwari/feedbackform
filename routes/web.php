<?php

use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\CustomerController;
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
    
/*------------------------------------
| CUSTOMER ROUTES
|------------------------------------*/
Route::get('/customers', [CustomerController::class, 'index'])
    ->name('customers.index');  