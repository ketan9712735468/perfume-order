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
        Schema::table('order_details', function (Blueprint $table) {
            // Drop the foreign key constraint and column
            $table->dropForeign(['employee_id']);
            $table->dropColumn('employee_id');
            $table->string('employee')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_details', function (Blueprint $table) {
            $table->dropColumn('employee');
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
        });
    }
};
