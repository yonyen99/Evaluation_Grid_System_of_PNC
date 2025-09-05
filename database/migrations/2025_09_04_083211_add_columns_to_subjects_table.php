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
        Schema::table('subjects', function (Blueprint $table) {
            $table->string('subject_type')->nullable()->after('name');
            $table->decimal('credit', 5, 2)->nullable()->after('subject_type');   // e.g. 1.00
            $table->decimal('nbhours', 6, 2)->nullable()->after('credit');       // e.g. 15.00
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subjects', function (Blueprint $table) {
            $table->dropColumn(['subject_type', 'credit', 'nbhours']);
        });
    }
};
