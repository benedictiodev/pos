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
            $table->softDeletes();
        });
        Schema::table('cash_in', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('cash_out', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('category_products', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('products', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('users', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('companies', function (Blueprint $table) {
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fund', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('cash_in', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('cash_out', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('category_products', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('products', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('users', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('companies', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
