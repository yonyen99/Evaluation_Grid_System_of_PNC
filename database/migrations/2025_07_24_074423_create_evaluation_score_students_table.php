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
        Schema::create('evaluation_score_students', function (Blueprint $table) {
            $table->id();
            $table->decimal('score', 5, 2)->nullable()->default(0);
            $table->foreignId('evaluation_grid_type_id')->constrained('evaluation_grid_types')->onDelete('cascade');
            $table->foreignId('evaluation_score_id')->constrained('evaluation_scores')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluation_score_students');
    }
};
