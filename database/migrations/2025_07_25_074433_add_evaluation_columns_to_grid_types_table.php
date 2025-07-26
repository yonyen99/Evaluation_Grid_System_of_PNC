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
        Schema::table('grid_types', function (Blueprint $table) {
            $table->boolean('has_evaluation')->default(false)->nullable();
            $table->decimal('total_evaluation', 8, 2)->default(0)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('grid_types', function (Blueprint $table) {
            $table->dropColumn(['has_evaluation', 'total_evaluation']);
        });
    }
};
