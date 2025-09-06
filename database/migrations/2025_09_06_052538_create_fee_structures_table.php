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
        Schema::create('fee_structures', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('course_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignUlid('batch_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->decimal('amount', 12, 2);
            $table->date('due_date')->nullable();
            $table->boolean('is_recurring')->default(false);
            $table->enum('frequency', ['monthly', 'quarterly', 'yearly'])->nullable();
            $table->unsignedInteger('late_fee_per_day')->default(50); // 👈 added
            $table->json('meta')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fee_structures');
    }
};
