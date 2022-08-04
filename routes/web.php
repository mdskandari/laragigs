<?php

use App\Http\Controllers\ListingController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\UserController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/roles', [PermissionController::class, 'Permission']);

Route::controller(ListingController::class)->group(function () {
    Route::middleware(['auth'])->group(function () {
        Route::get('/listing/create', 'create');
        Route::get('/listings/manage', 'manage');
        Route::get('/listings/{listing}/edit', 'edit')
            ->name('edit-listing');
        Route::put('/listings/{listing}', 'update');
        Route::delete('/listings/{listing}', 'destroy')
            ->name('delete-listing');
    });
    Route::get('/', 'index');
    Route::post('/listings', 'store');
    Route::get('/listing/{listing}', 'show')->where('id', '[0-9]+');

});


Route::controller(UserController::class)->group(function () {
    Route::middleware(['guest'])->group(function () {
        Route::get('/register', 'create')
            ->name('register-user');
        Route::post('/users', 'store');
        Route::get('/login', 'login')->name('login');
        Route::post('/users/authenticate', 'authenticate');
    });

    Route::middleware(['auth'])->group(function () {
        Route::post('/logout', 'logout')->name('logout');
    });
});


//Route::get('/', function () {
//    return Inertia::render('Welcome', [
//        'canLogin' => Route::has('login'),
//        'canRegister' => Route::has('register'),
//        'laravelVersion' => Application::VERSION,
//        'phpVersion' => PHP_VERSION,
//    ]);
//});
//
//Route::get('/dashboard', function () {
//    return Inertia::render('Dashboard');
//})->middleware(['auth', 'verified'])->name('dashboard');
//
//require __DIR__.'/auth.php';





