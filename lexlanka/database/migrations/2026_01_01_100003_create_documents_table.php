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
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('case_id')
                  ->constrained('legal_cases')
                  ->cascadeOnDelete();
            $table->foreignId('uploaded_by')
                  ->constrained('users')
                  ->restrictOnDelete();
            $table->string('file_path');
            $table->string('file_type');
            $table->enum('category', ['evidence', 'deeds', 'correspondence']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
