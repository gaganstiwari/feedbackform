    <?php

    use App\Http\Controllers\FeedbackController;
    use App\Http\Controllers\BlogController;
    use App\Http\Controllers\CustomerController; 

    use Illuminate\Support\Facades\Route;


    // Blog Routes
    Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
    Route::get('/blog/create', [BlogController::class, 'create'])->name('blog.create');
    Route::post('/blog', [BlogController::class, 'store'])->name('blog.store');
    Route::get('/blog/{blog}', [BlogController::class, 'show'])->name('blog.show');
    Route::get('/blog/{blog}/edit', [BlogController::class, 'edit'])->name('blog.edit');
    Route::put('/blog/{blog}', [BlogController::class, 'update'])->name('blog.update');
    Route::delete('/blog/{blog}', [BlogController::class, 'destroy'])->name('blog.destroy');
    
   
 
    // Main feedback page
    Route::get('/feedback', [FeedbackController::class, 'index'])->name('feedback.index');
    
    // Save feedback
    Route::post('/feedback/store', [FeedbackController::class, 'store'])->name('feedback.store');
    
    Route::get('/feedback/handle', [FeedbackController::class, 'handleLink']);

    Route::get('/customers', [CustomerController::class, 'index']);
    Route::get('/feedback', [FeedbackController::class, 'index'])->name('feedback.form');

