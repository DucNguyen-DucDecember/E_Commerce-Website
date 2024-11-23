<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('product_name', 120);
            $table->string('producty_slug', 120);
            $table->text('product_desc');
            $table->text('product_detail');
            $table->unsignedInteger('product_price');
            $table->unsignedInteger('stock_quantity');
            $table->tinyInteger('is_featured')->default(1);
            $table->enum('product_status', ['active', 'inactive', 'out_of_stock'])->default('active');
            $table->text('product_thumb');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('category_id');
            $table->foreign('category_id')->references('id')->on('product_categories')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
