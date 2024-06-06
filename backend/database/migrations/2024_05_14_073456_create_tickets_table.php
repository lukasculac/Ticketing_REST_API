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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->integer('worker_id');
            $table->string('department');
            $table->string('message');
            $table->string('response')->nullable()->default(null);
            $table->string('status')->default('pending'); // pending, opened, in_progress,  closed
            $table->string('priority')->default('low'); // low, medium, high
            $table->dateTime('opened_at')->nullable()->default(null);
            $table->dateTime('closed_at')->nullable()->default(null);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
