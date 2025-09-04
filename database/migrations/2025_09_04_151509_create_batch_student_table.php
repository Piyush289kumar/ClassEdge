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
        Schema::create('batch_student', function (Blueprint $table) {
            // $table->id();
            $table->foreignUlid('batch_id')->constrained()->cascadeOnDelete();
            $table->foreignUlid('student_id')->constrained()->cascadeOnDelete();
            $table->string('roll_no')->nullable()->index();
            $table->date('joined_on')->nullable();
            $table->timestamps();
            $table->primary(['batch_id', 'student_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('batch_student');
    }
};
