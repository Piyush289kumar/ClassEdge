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

            // Student details
            $table->foreignUlid('student_id')->nullable()->constrained()->cascadeOnUpdate()->cascadeOnDelete();

            $table->string('email');
            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->text('address')->nullable();

            $table->foreignUlid('batch_id')->nullable()->constrained()->nullOnDelete();

            $table->json('class_days')->nullable(); // Store selected days as JSON
            $table->string('mobile_number');
            $table->string('photo_path')->nullable(); // Uploaded photo

            $table->foreignUlid('course_id')->nullable()->constrained()->cascadeOnUpdate()->restrictOnDelete();

            $table->date('dob')->nullable();
            $table->enum('gender', ['Female', 'Male'])->nullable();
            $table->string('guardian_number')->nullable();
            $table->enum('payment_method', ['Cash', 'UPI', 'Other'])->nullable();
            $table->date('admission_date')->nullable();
            $table->date('admitted_on')->index();
            $table->boolean('fee_submitted')->default(false);
            $table->json('heard_about')->nullable(); // Change from string to json

            // Correct foreign key definition
            $table->unsignedBigInteger('store_id')->nullable();
            $table->foreign('store_id')->references('id')->on('stores')->nullOnDelete();

            $table->string('occupation')->nullable();
            $table->json('meta')->nullable();
            $table->enum('status', ['pending', 'admitted', 'rejected', 'withdrawn', 'completed'])->default('admitted')->index();
            $table->string('payment_reference')->nullable()->index();

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
