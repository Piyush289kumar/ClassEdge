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
        Schema::create('students', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->string('roll_no')->nullable()->index();
            $table->string('registration_no')->nullable()->unique();
            $table->date('dob')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable()->index();
            $table->string('email')->nullable()->unique();
            $table->string('phone')->nullable()->index();
            $table->string('alt_phone')->nullable();
            $table->string('address_line1')->nullable();
            $table->string('address_line2')->nullable();
            $table->string('city')->nullable()->index();
            $table->string('state')->nullable()->index();
            $table->string('country')->default('IN')->index();
            $table->string('zip')->nullable();
            $table->string('photo_path')->nullable();
            $table->json('meta')->nullable(); // extensibility
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
