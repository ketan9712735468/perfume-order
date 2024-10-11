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
            $table->decimal('freight', 10, 2)->nullable()->change();
            $table->foreignId('tracking_company_id')->nullable()->change();
            $table->foreignId('stock_control_status_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_details', function (Blueprint $table) {
            // Revert the changes to not nullable
            $table->decimal('freight', 10, 2)->nullable(false)->change();
            $table->foreignId('tracking_company_id')->nullable(false)->change();
            $table->foreignId('stock_control_status_id')->nullable(false)->change();
        });
    }
};
