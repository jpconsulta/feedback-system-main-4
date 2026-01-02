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
        Schema::create('library_services', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Service name (e.g., "Interlibrary Loan")
            $table->text('description')->nullable(); // Longer text, optional

            // Stores ratings like 4.50. (Total digits: 3, Decimal places: 2)
            // Default is 0.00 until someone rates it.
            $table->decimal('avg_rating', 3, 2)->default(0.00);

            $table->timestamps(); // Adds created_at and updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('library_services');
    }
};
