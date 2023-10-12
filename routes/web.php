<?php

use App\Http\Controllers\Api\PortfoliosController as ApiPortfoliosController;
use App\Http\Controllers\JobsController;
use App\Http\Controllers\PortfoliosController;
use App\Http\Controllers\ProfilesController;
use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::get('/test', [ApiPortfoliosController::class, 'index']);
Route::get('/', function () {
    return Inertia::render('Index');
});
//if error [login]route something like that is shown then add ->name('login'); name to that route
Route::middleware(['jwt', 'guest'])->group(function () {
    Route::get("login", [UsersController::class, "login"])->name('login');
    Route::get("register-client", [UsersController::class, "registerClient"]);
    Route::get("register-freelancer", [UsersController::class, "registerFreelancer"]);
});

Route::middleware(['auth'])->group(function () {
    Route::get('jobs', [JobsController::class, 'index']);
    Route::get('jobs/{id}', [JobsController:: class, 'find']);
    Route::get('freelancers', [UsersController::class, 'index']);
    Route::get('freelancers/{freelancer_id}', [UsersController::class, 'find']);
    Route::get('profile', [ProfilesController::class, 'index']);
    Route::get('portfolio', [PortfoliosController::class, 'index']);
});

Route::get('{slug}', function () {
    return redirect('/');
});
