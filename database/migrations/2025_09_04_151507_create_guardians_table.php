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
        Schema::create('guardians', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->enum('relation', ['father', 'mother', 'brother', 'sister', 'guardian', 'other'])->nullable()->index();
            $table->string('email')->nullable()->index();
            $table->string('phone')->nullable()->index();
            $table->string('occupation')->nullable();
            $table->string('address_line1')->nullable();
            $table->string('address_line2')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->default('IN');
            $table->string('zip')->nullable();
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
        Schema::dropIfExists('guardians');
    }
};
