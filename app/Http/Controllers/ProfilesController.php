<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Inertia\Inertia;

class ProfilesController extends Controller
{
    public function index(){
        $userID = auth()->user()->id;
        return Inertia::render('Profile', [
            'userID' => $userID
        ]);
    }
}
