<?php

namespace App\Http\Controllers;

use App\Models\Portfolio;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PortfoliosController extends Controller
{
    public function index(){
        $userID = auth()->user()?->id;
        return Inertia::render('Portfolio', [
            'userID' => $userID
        ]);
    }
}
