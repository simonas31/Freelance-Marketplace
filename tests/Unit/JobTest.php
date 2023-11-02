<?php

namespace Tests\Unit;

use App\Models\HiredFreelancer;
use App\Models\Job;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class JobTest extends TestCase
{
    use RefreshDatabase;

    public function test_job_has_one_transaction(): void
    {
        $job = Job::factory()->create();
        $tx = Transaction::factory(1)->create(['job_id' => $job->id]);

        $this->assertInstanceOf(Transaction::class, $job->transaction);
    }

    public function test_job_has_one_hired_freelancer(): void
    {
        $job = Job::factory()->create();
        $hf = HiredFreelancer::factory(1)->create(['job_id' => $job->id]);

        $this->assertInstanceOf(HiredFreelancer::class, $job->hiredFreelancer);
    }

    public function test_job_belongs_to_user(): void
    {
        $user = User::factory()->create();
        $job = Job::factory()->create(['user_id' => $user->id]);

        $retrievedUser = $job->user;

        $this->assertInstanceOf(User::class, $retrievedUser);

        $this->assertEquals($user->id, $retrievedUser->id);
    }
}
