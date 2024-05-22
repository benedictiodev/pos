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
        Schema::create('remarks_cash_flow', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id');
            $table->string('name');
            $table->string('type');
            $table->softDeletes();
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
        Schema::dropIfExists('remarks_cash_flow');
    }
};
