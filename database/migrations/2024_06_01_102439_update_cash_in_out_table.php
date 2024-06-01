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
        Schema::table('cash_in', function (Blueprint $table) {
            $table->text('remarks_from_master')->nullable();
        });
        Schema::table('cash_out', function (Blueprint $table) {
            $table->text('remarks_from_master')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cash_in', function (Blueprint $table) {
            $table->dropColumn('remarks_from_master');
        });
        Schema::table('cash_out', function (Blueprint $table) {
            $table->dropColumn('remarks_from_master');
        });
    }
};
