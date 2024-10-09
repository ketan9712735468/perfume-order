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
        Schema::create('order_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained()->onDelete('cascade');
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->date('email_date');
            $table->date('response_date');
            $table->foreignId('vendor_id')->constrained()->onDelete('cascade');
            $table->foreignId('type_id')->constrained()->onDelete('cascade');
            $table->string('sales_order')->nullable();
            $table->string('invoice_number')->nullable();
            $table->decimal('freight', 10, 2);
            $table->decimal('total_amount', 10, 2);
            $table->date('paid_date')->nullable();
            $table->decimal('paid_amount', 10, 2)->nullable();
            $table->string('variants')->nullable();
            $table->integer('sb')->nullable();
            $table->integer('rb')->nullable();
            $table->integer('units')->nullable();
            $table->integer('received')->nullable();
            $table->date('delivery_date')->nullable();
            $table->foreignId('tracking_company_id')->constrained()->onDelete('cascade');
            $table->string('tracking_number')->nullable();
            $table->text('note')->nullable();
            $table->foreignId('stock_control_status_id')->constrained()->onDelete('cascade');
            $table->string('order_number')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_details');
    }
};
