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
        Schema::create('admissions', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('student_id')->nullable()->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignUlid('course_id')->nullable()->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignUlid('batch_id')->nullable()->constrained()->nullOnDelete();
            $table->date('admitted_on')->index();
            $table->enum('status', ['pending', 'admitted', 'rejected', 'withdrawn', 'completed'])->default('admitted')->index();
            $table->decimal('fee_total', 12, 2)->nullable();
            $table->decimal('fee_paid', 12, 2)->nullable();
            $table->string('payment_reference')->nullable()->index();
            $table->json('meta')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['student_id', 'course_id', 'batch_id', 'admitted_on'], 'uniq_student_course_batch_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admissions');
    }
};
