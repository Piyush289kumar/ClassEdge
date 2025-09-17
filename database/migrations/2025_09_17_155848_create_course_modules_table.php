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
        Schema::create('course_modules', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->ulid('course_id'); // foreign key reference
            $table->string('title');
            $table->text('description')->nullable();
            $table->json('resources')->nullable(); // images, videos, PDFs, etc.
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_modules');
    }
};
