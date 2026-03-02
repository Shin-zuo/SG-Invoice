<?php

use App\Http\Controllers\AnalyticsController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\DashboardController;
use App\Http\Middleware\Authenticate;
/*|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    if (auth()->check()) {
        // Option A: Redirect to the named route
        return redirect()->route('dashboard');
        
        // Option B: Call the controller method directly (Less common)
        // return (new DashboardController)->index();
    }

    return view('auth.login');

});

// This handles the redirect from the Authenticate middleware
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('login', [LoginController::class, 'authenticate'])->name('login.authenticate');

Route::middleware('auth')->group(function(){

// Invoice routes - Added the missing route that you were trying to redirect to
Route::get('/invoice', [InvoiceController::class, 'index'])->name('invoice');
Route::post('/invoice/store', [InvoiceController::class, 'store'])->name('invoice.store');
Route::get('/invoice/{id}', [InvoiceController::class, 'show'])->name('invoice.show');  
Route::get('/invoice/{id}/export', [InvoiceController::class, 'export'])->name('invoice.export');
Route::put('/invoice/{id}', [App\Http\Controllers\InvoiceController::class, 'update'])->name('invoice.update');

Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics');
Route::get('/analytics/chart-data', [AnalyticsController::class, 'getChartData'])->name('analytics.chart');

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

});




