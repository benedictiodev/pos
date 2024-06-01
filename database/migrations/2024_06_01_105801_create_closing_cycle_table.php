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
        Schema::create('closing_cycle', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id');
            $table->string('periode');
            $table->bigInteger('equity');
            $table->bigInteger('income')->default(0);
            $table->bigInteger('expenditure')->default(0);
            $table->bigInteger('profit')->default(0);
            $table->boolean('is_done')->default(false);
            $table->timestamps();

            $table->index('company_id');
            $table->foreign('company_id')->references('id')->on('companies');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('closing_cycle');
    }
};
