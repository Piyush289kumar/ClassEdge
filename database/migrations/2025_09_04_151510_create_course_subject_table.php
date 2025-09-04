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
        Schema::create('course_subject', function (Blueprint $table) {
            // $table->id();
            $table->foreignUlid('course_id')->constrained()->cascadeOnDelete();
            $table->foreignUlid('subject_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('semester')->nullable()->index();
            $table->timestamps();
            $table->primary(['course_id', 'subject_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_subject');
    }
};
