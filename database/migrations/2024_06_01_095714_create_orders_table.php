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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id');
            $table->string('id_order');
            $table->string('customer_name')->nullable();
            $table->string('cashier_name');
            $table->datetime('datetime');
            $table->integer('total_payment');
            $table->integer('payment');
            $table->integer('change');
            $table->string('payment_method');
            $table->string('order_type');
            $table->string('status');
            $table->text('remarks')->nullable();
            $table->timestamps();

            $table->index('company_id');
            $table->foreign('company_id')->references('id')->on('companies');
        });

        Schema::table('cash_in', function (Blueprint $table) {
            $table->foreignId('order_id')->nullable()->default(null);

            $table->index('order_id');
            $table->foreign('order_id')->references('id')->on('orders');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');

        Schema::table('cash_in', function (Blueprint $table) {
            $table->dropColumn('order_id');
        });
    }
};
