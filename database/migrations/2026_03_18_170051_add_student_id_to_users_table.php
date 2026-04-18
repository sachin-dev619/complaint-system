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
        Schema::table('users', function (Blueprint $table) {

            $table->foreignId('student_id')->nullable()->constrained()->cascadeOnDelete();

            $table->enum('role',['student','admin'])->default('student');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['student_id']); // foreign key remove
            $table->dropColumn('student_id');    // column remove
            $table->dropColumn('role');          // role remove
        });
    }
};
