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
        Schema::create('laravel_seeder_logs', function (Blueprint $table) {
            $table->id();
            $table->string('seeder'); // Stores the class name
            $table->integer('batch');  // Allows you to "rollback" a group
            $table->timestamp('ran_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laravel_seeder_logs');
    }
};
