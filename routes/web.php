<?php

use App\Http\Controllers\ChatsController;
use App\Http\Controllers\HiredFreelancersController;
use App\Http\Controllers\JobsController;
use App\Http\Controllers\PortfoliosController;
use App\Http\Controllers\ProfilesController;
use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Date;
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
Route::get('/', function () {
    return Inertia::render('Index');
});

Route::middleware(['jwt', 'guest'])->group(function () {
    Route::get("login", [UsersController::class, "login"])->name('login');
    Route::get("register-client", [UsersController::class, "registerClient"]);
    Route::get("register-freelancer", [UsersController::class, "registerFreelancer"]);
});

Route::middleware(['auth', 'users:admin,client,freelancer'])->group(function () {
    Route::get('profile', [ProfilesController::class, 'index']);
    Route::get('chats', [ChatsController::class, 'userChats']);
    Route::get('jobs/{id}', [JobsController::class, 'find']);
    Route::get('edit-jobs/{id}', [JobsController::class, 'editJob']);
});

Route::middleware(['auth', 'users:client'])->group(function () {
    Route::get('freelancers/{freelancer_id}', [UsersController::class, 'find']);
    Route::get('create-job', [JobsController::class, 'create']);
    Route::get('your-jobs', [JobsController::class, 'userJobs']);
    Route::get('hired-freelancers', [HiredFreelancersController::class, 'userHiredFreelancers']);
    Route::get('applied-freelancers', [HiredFreelancersController::class, 'appliedFreelancers']);
});

Route::middleware(['auth', 'users:freelancer'])->group(function () {
    Route::get('portfolio', [PortfoliosController::class, 'index']);
    Route::get('jobs', [JobsController::class, 'index']);
});

Route::middleware(['auth', 'users:admin'])->group(function () {
    Route::get('users', [UsersController::class, 'usersToConfirm']);
    Route::get('confirm-jobs', [JobsController::class, 'confirmJobs']);
});


Route::get('{slug}', function () {
    return redirect('/');
});
