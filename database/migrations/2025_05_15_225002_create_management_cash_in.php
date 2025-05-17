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
        Schema::create('management_cash_in', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('fund')->default(0);
            $table->text('remarks');
            $table->dateTime('datetime');
            $table->foreignId('type_fund_id')->nullable()->constrained('management_funds');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('management_cash_in');
    }
};
