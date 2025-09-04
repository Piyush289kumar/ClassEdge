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
        Schema::create('guardian_student', function (Blueprint $table) {
            $table->foreignUlid('student_id')->constrained()->cascadeOnDelete();
            $table->foreignUlid('guardian_id')->constrained()->cascadeOnDelete();
            $table->enum('relation_type', ['father', 'mother', 'brother', 'sister', 'guardian', 'other'])->nullable();
            $table->boolean('is_primary')->default(false)->index();
            $table->timestamps();
            $table->primary(['student_id', 'guardian_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guardian_student');
    }
};
