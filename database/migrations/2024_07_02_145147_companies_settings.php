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
        Schema::create('companies_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId("company_id")->nullable();
            $table->double('latitude')->nullable();
            $table->double('longitude')->nullable();
            $table->double('distance')->nullable();
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
        Schema::dropIfExists('companies_settings');
    }
};
