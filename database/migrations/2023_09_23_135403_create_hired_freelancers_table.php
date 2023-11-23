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
        Schema::create('hired_freelancers', function (Blueprint $table) {
            $table->id();
            $table->integer('freelancer_id');
            $table->integer('client_id');
            $table->integer('job_id');
            $table->dateTime('hire_date');
            $table->boolean('confirmed')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hired_freelancers');
    }
};
