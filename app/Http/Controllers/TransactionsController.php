<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TransactionsController extends Controller
{
    public function index()
    {
        $user_id = auth()->id();

        $transactions = Transaction::with(['job.hiredFreelancer.freelancer', 'job.hiredFreelancer.client'])
                ->where('user_id', '=', $user_id)
                ->orWhere('receiver', '=', $user_id)
                ->get()
                ->toArray();
        
        return Inertia::render('Transactions', [
            'transactions' => $transactions
        ]);
    }
}
