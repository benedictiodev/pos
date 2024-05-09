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
        Schema::table('fund', function (Blueprint $table) {
            $table->dropColumn('datetime');
            $table->string('type');
        });
        Schema::table('cash_in', function (Blueprint $table) {
            $table->string('type');
        });
        Schema::table('cash_out', function (Blueprint $table) {
            $table->string('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fund', function (Blueprint $table) {
            $table->datetime('datetime');
            $table->dropColumn('type');
        });
        Schema::table('cash_in', function (Blueprint $table) {
            $table->dropColumn('type');
        });
        Schema::table('cash_out', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
};
