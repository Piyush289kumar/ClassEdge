<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('duration', ['1 month', '3 month', '6 month', '1 year'])->default('1 month');
            $table->unsignedInteger('credits')->default(0);
            $table->json('meta')->nullable(); // Store extra info if needed
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
