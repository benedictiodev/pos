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
        Schema::create('cash_in', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id');
            $table->bigInteger('fund');
            $table->text('remark');
            $table->datetime('datetime');
            $table->timestamps();
        });

        Schema::table('cash_in', function (Blueprint $table) {
            $table->index('company_id');
            $table->foreign('company_id')->references('id')->on('companies');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cash_in');
    }
};
