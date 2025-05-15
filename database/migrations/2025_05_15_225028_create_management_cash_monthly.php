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
        Schema::create('management_cash_monthly', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('debit')->default(0);
            $table->bigInteger('kredit')->default(0);
            $table->bigInteger('amount')->default(0);
            $table->date('date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('management_cash_monthly');
    }
};
