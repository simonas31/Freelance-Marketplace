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
        Schema::create('portfolios', function (Blueprint $table) {
            $table->id();
            $table->text('resume');
            $table->text('work_fields');
            $table->integer('work_experience')->nullable();
            $table->integer('user_id');
            $table->date('posted_time');
            $table->boolean('posted')->default(0);
            //can post thhis profile if this value canPost and int portfolio can_post is true after they setup their settings
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('portfolios');
    }
};
