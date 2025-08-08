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
        Schema::table('evaluation_score_students', function (Blueprint $table) {
            $table->decimal('detail_evaluation_total', 5, 2)->default(0);
            $table->boolean('has_detail_evaluation')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('evaluation_score_students', function (Blueprint $table) {
            $table->dropColumn(['detail_evaluation_total', 'has_detail_evaluation']);
        });
    }
};
