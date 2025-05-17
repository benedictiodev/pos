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
        Schema::create('management_cash_closing_cycle', function (Blueprint $table) {
            $table->id();
            $table->string('periode');
            $table->bigInteger('income_operational')->default(0);
            $table->bigInteger('expenditure_operational')->default(0);
            $table->bigInteger('amount_operational')->default(0);
            $table->bigInteger('for_profit')->default(0);
            $table->bigInteger('for_development')->default(0);
            $table->bigInteger('for_zakat')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('management_cash_closing_cycle');
    }
};
