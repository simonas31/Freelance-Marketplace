<?php

use App\Http\Controllers\Api\ChatsController;
use App\Http\Controllers\Api\HiredFreelancersController;
use App\Http\Controllers\Api\JobsController;
use App\Http\Controllers\Api\MessagesController;
use App\Http\Controllers\Api\PortfoliosController;
use App\Http\Controllers\Api\ProfilesController;
use App\Http\Controllers\Api\RatingsController;
use App\Http\Controllers\Api\TransactionsController;
use App\Http\Controllers\Api\UsersController;
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
Route::post("users", [UsersController::class, "register"]);

Route::group(["middleware" => ["auth:api"]], function () {
    Route::get('freelancers', [UsersController::class, 'freelancers']);
    Route::get("auth", [UsersController::class, "user"]);
    Route::get("refresh", [UsersController::class, "refreshToken"]);
    Route::get("logout", [UsersController::class, "logout"]);

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

    Route::get('ratings', [RatingsController::class, 'index']);
    Route::post('ratings', [RatingsController::class, 'store']);
    Route::get('ratings/{freelancer_id}', [RatingsController::class, 'show']);
    Route::put('ratings/{freelancer_id}', [RatingsController::class, 'update']);
    Route::get('ratings/{freelancer_id}/{client_id}', [RatingsController::class, 'clientFreelancerRating']);
    Route::delete('ratings/{rating_id}', [RatingsController::class, 'destroy']);

    //hierachical connection
    Route::get('users', [UsersController::class, 'listUsers']);
    Route::get('users/{user_id}', [UsersController::class, 'specificUser']);
    Route::delete('users/{user_id}', [UsersController::class, 'delete']);
    Route::patch('users/{user_id}', [UsersController::class, 'confirmUser']);
    Route::put('users/{user_id}', [UsersController::class, 'updateRating']);

    Route::get('chats_index', [ChatsController::class, 'index']);
    Route::get('show_chat/{chat_id}', [ChatsController::class, 'show']);
    Route::get('users/{user_id}/chats', [ChatsController::class, 'listUserChats']);
    Route::post('users/{user_id}/{receiver}/chats', [ChatsController::class, 'store']);
    Route::put('users/{user_id}/chats/{chat_id}', [ChatsController::class, 'update']);
    Route::delete('users/{user_id}/chats/{chat_id}', [ChatsController::class, 'destroy']);
    Route::get('users/{user_id}/chats/{chat_id}', [ChatsController::class, 'UserChat']);
    
    Route::get('show_messages/{chat_id}', [MessagesController::class, 'show_messages']);
    Route::get('users/{user_id}/chats/{chat_id}/messages', [MessagesController::class, 'listUserChatMessages']);
    Route::post('users/{user_id}/chats/{chat_id}/messages', [MessagesController::class, 'store']);
    Route::put('users/{user_id}/chats/{chat_id}/messages/{message_id}', [MessagesController::class, 'update']);
    Route::delete('users/{user_id}/chats/{chat_id}/messages/{message_id}', [MessagesController::class, 'delete']);
    Route::get('users/{user_id}/chats/{chat_id}/messages/{message_id}', [MessagesController::class, 'UserChatMessage']);

    Route::get('jobs', [JobsController::class, 'index']);
    Route::put('users/{user_id}/update_jobs/{job_id}', [JobsController::class, 'updateJob']);
    Route::get('show_job/{user_id}', [JobsController::class, 'show']);
    Route::patch('jobs/{job_id}', [JobsController::class, 'confirmCreation']);//
    Route::get('users/{user_id}/jobs', [JobsController::class, 'listUserJobs']);
    Route::post('users/{user_id}/jobs', [JobsController::class, 'store']);
    Route::put('users/{user_id}/jobs/{job_id}', [JobsController::class, 'update']);//
    Route::delete('users/{user_id}/jobs/{job_id}', [JobsController::class, 'destroy']);
    Route::get('users/{user_id}/jobs/{job_id}', [JobsController::class, 'UserJob']);

    Route::get('show_transaction/{transaction_id}', [TransactionsController::class, 'show']);
    Route::get('users/{user_id}/jobs/{job_id}/transactions', [TransactionsController::class, 'listUserJobTransactions']);
    Route::post('users/{user_id}/jobs/{job_id}/transactions', [TransactionsController::class, 'store']);
    Route::put('users/{user_id}/jobs/{job_id}/transactions/{transaction_id}', [TransactionsController::class, 'update']);
    Route::delete('users/{user_id}/jobs/{job_id}/transactions/{transaction_id}', [TransactionsController::class, 'destroy']);
    Route::get('users/{user_id}/jobs/{job_id}/transactions/{transaction_id}', [TransactionsController::class, 'UserJobTransaction']);

    Route::get('show_hf/{hired_freelancer_id}', [HiredFreelancersController::class, 'show']);
    Route::get('users/{user_id}/jobs/{job_id}/hiredfreelancers', [HiredFreelancersController::class, 'listUserJobHireFreelancers']);
    Route::post('users/{user_id}/jobs/{job_id}/hiredfreelancers', [HiredFreelancersController::class, 'store']);
    Route::put('users/{user_id}/jobs/{job_id}/hiredfreelancers/{hired_freelancer_id}', [HiredFreelancersController::class, 'update']);
    Route::delete('users/{user_id}/jobs/{job_id}/hiredfreelancers/{hired_freelancer_id}', [HiredFreelancersController::class, 'destroy']);
    Route::get('users/{user_id}/jobs/{job_id}/hiredfreelancers/{hired_freelancer_id}', [HiredFreelancersController::class, 'UserJobHiredFreelancer']);
});
