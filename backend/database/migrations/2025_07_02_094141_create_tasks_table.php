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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table
                ->foreignUuid('user_id')
                ->constrained()
                ->onDelete('cascade');
            $table
                ->string('title')
                ->limit(100)
                ->unique();
            $table
                ->text('content')
                ->nullable();
            $table->enum('status', [
                'TO_DO', 
                'IN_PROGRESS', 
                'DONE'
            ]);
            $table
                ->string('image_path')
                ->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
