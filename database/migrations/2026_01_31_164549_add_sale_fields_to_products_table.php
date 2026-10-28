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
        Schema::table('products', function (Blueprint $table) {
            $table->boolean('is_on_special_sale')->default(false)->after('price');
            $table->unsignedBigInteger('sale_price')->nullable()->after('is_on_special_sale');
            $table->timestamp('sale_start_at')->nullable()->after('sale_price');
            $table->timestamp('sale_end_at')->nullable()->after('sale_start_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['is_on_special_sale']);
            $table->dropColumn(['sale_price']);
            $table->dropColumn(['sale_start_at']);
            $table->dropColumn(['sale_end_at']);
        });
    }
};
