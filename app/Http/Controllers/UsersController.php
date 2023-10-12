<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Http\Controllers\Controller;
use App\Models\Profile;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('AvailableFreelancers');
    }

    public function find($freelancer_id)
    {
        $freelancer = User::where([['users.id', '=', $freelancer_id]])
        ->leftJoin('profiles', 'users.id', '=', 'profiles.user_id')
        ->leftJoin('portfolios', 'users.id', '=', 'portfolios.user_id')
        ->where([['portfolios.posted', '=', 1], ['profiles.posted', '=', 1]]);

        $freelancer->select(
            'users.id',
            'users.name',
            'users.surname',
            'users.rating',
            'profiles.country',
            'portfolios.work_fields',
            'portfolios.work_experience',
            'portfolios.resume',
        );

        return Inertia::render('Freelancer',[
            'freelancer' => $freelancer->get()->first()
        ]);
    }
    /**
     * Check user authentication for login form.
     */
    public function login(Request $request)
    {
        //if authenticated then return main page, else return login page
        return Inertia::render('Login');
    }

    /**
     * Check user authentication for register form.
     */
    public function registerClient(Request $request)
    {
        return Inertia::render('RegisterClient');
    }

    /**
     * Check user authentication for register form.
     */
    public function registerFreelancer(Request $request)
    {
        return Inertia::render('RegisterFreelancer');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }
}
