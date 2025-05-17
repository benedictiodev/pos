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
        Schema::create('management_cash_allocation_funds_tables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('from_type_id')->nullable()->constrained('management_funds');
            $table->foreignId('to_type_id')->nullable()->constrained('management_funds');
            $table->bigInteger('amount')->default(0);
            $table->dateTime('datetime');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('management_cash_allocation_funds_tables');
    }
};
