<?php

use App\Http\Controllers\Api\ChatsController;
use App\Http\Controllers\Api\HiredFreelancersController;
use App\Http\Controllers\Api\JobsController;
use App\Http\Controllers\Api\MessagesController;
use App\Http\Controllers\Api\PortfoliosController;
use App\Http\Controllers\Api\ProfilesController;
use App\Http\Controllers\Api\RatingsController;
use App\Http\Controllers\Api\TransactionsController;
use App\Http\Controllers\API\UsersController;
use App\Models\HiredFreelancer;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post("login", [UsersController::class, "login"]);
Route::post("register", [UsersController::class, "register"]);

Route::group(["middleware" => ["auth:api"]], function () {
    Route::get('freelancers', [UsersController::class, 'freelancers']);
    Route::get("auth", [UsersController::class, "user"]);
    Route::get("refresh", [UsersController::class, "refreshToken"]);
    Route::get("logout", [UsersController::class, "logout"]);
    Route::delete('users/{user_id}', [UsersController::class, 'delete']);
    Route::put('users/{user_id}', [UsersController::class, 'updateRating']);

    Route::get('transactions', [TransactionsController::class, 'index']);
    Route::post('transactions', [TransactionsController::class, 'store']);
    Route::get('transactions/{transaction_id}', [TransactionsController::class, 'show']);
    Route::put('transactions/{transaction_id}', [TransactionsController::class, 'update']);
    Route::delete('transactions/{transaction_id}', [TransactionsController::class, 'destroy']);

    Route::get('profiles', [ProfilesController::class, 'index']);
    Route::post('profiles', [ProfilesController::class, 'store']);
    Route::get('profiles/{user_id}', [ProfilesController::class, 'show']);
    Route::post('profiles/{user_id}', [ProfilesController::class, 'update']);
    Route::delete('profiles/{user_id}', [ProfilesController::class, 'destroy']);

    Route::get('portfolios', [PortfoliosController::class, 'index']);
    Route::post('portfolios', [PortfoliosController::class, 'store']);
    Route::get('portfolios/{user_id}', [PortfoliosController::class, 'show']);
    Route::put('portfolios/{user_id}', [PortfoliosController::class, 'update']);
    Route::delete('portfolios/{user_id}', [PortfoliosController::class, 'destroy']);

    Route::get('messages/{chat_id}', [MessagesController::class, 'show_messages']);
    Route::post('messages', [MessagesController::class, 'store']);
    Route::put('messages/{message_id}', [MessagesController::class, 'update']);
    Route::delete('messages/{message_id}', [MessagesController::class, 'delete']);
    
    Route::get('jobs', [JobsController::class, 'index']);
    Route::post('jobs', [JobsController::class, 'store']);
    Route::get('jobs/{job_id}', [JobsController::class, 'show']);
    Route::put('jobs/{job_id}', [JobsController::class, 'update']);
    Route::delete('jobs/{job_id}', [JobsController::class, 'destroy']);

    Route::get('hiredfreelancers', [HiredFreelancersController::class, 'index']);
    Route::post('hiredfreelancers', [HiredFreelancersController::class, 'store']);
    Route::get('hiredfreelancers/{hired_freelancer_id}', [HiredFreelancersController::class, 'show']);
    Route::put('hiredfreelancers/{hired_freelancer_id}', [HiredFreelancersController::class, 'update']);
    Route::delete('hiredfreelancers/{hired_freelancer_id}', [HiredFreelancersController::class, 'destroy']);

    Route::get('chats', [ChatsController::class, 'index']);
    Route::post('chats', [ChatsController::class, 'store']);
    Route::get('chats/{chat_id}', [ChatsController::class, 'show']);
    Route::put('chats/{chat_id}', [ChatsController::class, 'update']);
    Route::delete('chats/{chat_id}', [ChatsController::class, 'destroy']);

    Route::get('ratings', [RatingsController::class, 'index']);
    Route::post('ratings', [RatingsController::class, 'store']);
    Route::get('ratings/{freelancer_id}', [RatingsController::class, 'show']);
    Route::put('ratings/{freelancer_id}', [RatingsController::class, 'update']);
    Route::delete('ratings/{rating_id}', [RatingsController::class, 'destroy']);

});
