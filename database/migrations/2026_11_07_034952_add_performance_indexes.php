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
        Schema::table('categories', function (Blueprint $table) {
            $table->index('is_active');
            $table->index('name');
        });

        Schema::table('subcategories', function (Blueprint $table) {
            $table->index('category_id');
            $table->index('is_active');
            $table->index('name');
            $table->index(['category_id', 'is_active']);
        });

        Schema::table('products', function (Blueprint $table) {
            $table->index('category_id');
            $table->index('subcategory_id');
            $table->index('is_active');
            $table->index(['category_id', 'is_active']);
            $table->index(['subcategory_id', 'is_active']);
            $table->index(['is_active', 'id']);
            $table->index('new_price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex(['category_id']);
            $table->dropIndex(['subcategory_id']);
            $table->dropIndex(['is_active']);
            $table->dropIndex(['category_id', 'is_active']);
            $table->dropIndex(['subcategory_id', 'is_active']);
            $table->dropIndex(['is_active', 'id']);
            $table->dropIndex(['new_price']);
        });

        Schema::table('subcategories', function (Blueprint $table) {
            $table->dropIndex(['category_id']);
            $table->dropIndex(['is_active']);
            $table->dropIndex(['name']);
            $table->dropIndex(['category_id', 'is_active']);
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->dropIndex(['is_active']);
            $table->dropIndex(['name']);
        });
    }
};
