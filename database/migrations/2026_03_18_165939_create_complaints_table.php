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
        Schema::create('complaints', function (Blueprint $table) {
            $table->id();

            $table->string('complaint_no')->unique();

            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            $table->foreignId('category_id');
            $table->foreignId('subcategory_id')->nullable();

            // REQUIRED FIELDS ✅
            $table->string('title');
            $table->text('complaint_text');

            $table->enum('priority', ['Low', 'Medium', 'High'])->default('Medium');

            $table->string('file')->nullable();

            // SYSTEM FIELDS
            $table->enum('status', ['Pending', 'In Progress', 'Resolved'])
                ->default('Pending');

            $table->text('admin_remark')->nullable();
            $table->timestamp('resolved_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('complaints');
    }
};
