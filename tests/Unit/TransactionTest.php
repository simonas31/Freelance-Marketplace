<?php

namespace Tests\Unit;

use App\Models\Job;
use App\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TransactionTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_transaction_has_one_job(): void
    {
        $job = Job::factory()->create();

        $transaction = Transaction::factory()->create(['job_id' => $job->id]);

        $retrievedJob = $transaction->job;

        $this->assertInstanceOf(Job::class, $retrievedJob);
        $this->assertEquals($job->id, $retrievedJob->id);
    }
}
