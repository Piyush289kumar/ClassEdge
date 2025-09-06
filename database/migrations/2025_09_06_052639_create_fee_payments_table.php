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
        Schema::create('fee_payments', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('student_id')->constrained()->cascadeOnDelete();
            $table->foreignUlid('admission_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignUlid('fee_structure_id')->nullable()->constrained()->nullOnDelete();
            $table->decimal('amount', 12, 2);
            $table->date('paid_on')->nullable();
            $table->enum('status', ['pending', 'paid', 'partial', 'overdue', 'waived', 'refunded'])->default('pending')->index();
            $table->enum('payment_mode', ['cash', 'card', 'bank', 'upi', 'cheque'])->nullable();
            $table->string('reference_number')->nullable()->index();
            $table->decimal('late_fee', 12, 2)->default(0);
            $table->decimal('discount', 12, 2)->default(0);
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
        Schema::dropIfExists('fee_payments');
    }
};
