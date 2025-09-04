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
        Schema::create('batches', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('course_id')->nullable()->constrained()->restrictOnDelete();
            $table->foreignUlid('classroom_id')->nullable()->constrained()->nullOnDelete();
            $table->string('code')->unique(); // section code e.g. CSE-2025-A
            $table->string('name')->nullable(); // Section A
            $table->date('starts_at')->nullable()->index();
            $table->date('ends_at')->nullable()->index();
            $table->unsignedInteger('capacity')->nullable();
            $table->enum('status', ['draft', 'active', 'completed', 'archived'])->default('active')->index();
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
        Schema::dropIfExists('batches');
    }
};
