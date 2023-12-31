<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->text('description');
            $table->text('work_fields');
            $table->string('job_title', 100);
            $table->float('pay_amount')->default(0);
            $table->dateTime('posted_time');
            $table->integer('user_id');
            $table->integer('freelancer_id')->default(-1);
            $table->integer('transaction_id')->default(-1);
            $table->boolean('creation_confirmed')->default(0);
            $table->boolean('finished')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jobs');
    }
};
