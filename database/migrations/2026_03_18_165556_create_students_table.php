<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
             $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('roll_no');

            $table->foreignId('class_models_id')->constrained()->cascadeOnDelete();
            $table->foreignId('division_id')->constrained()->cascadeOnDelete();

            $table->string('gender')->nullable();
            $table->string('phone')->nullable();

            $table->timestamps();

            $table->unique(['roll_no','class_models_id','division_id']); // important
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
