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
        Schema::table('students', function (Blueprint $table) {
            $table->string('student_id')->unique()->after('id');
            $table->enum('gender', ['male', 'female', 'other'])->after('last_name');
            $table->string('email')->unique()->after('gender');

            $table->unsignedBigInteger('province_id')->after('email');
            $table->foreign('province_id')->references('id')->on('provinces')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropForeign(['province_id']);
            $table->dropColumn(['student_id', 'gender', 'email', 'province_id']);
        });
    }
};
