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
        Schema::create('product_categories', function (Blueprint $table) {
            $table->id(); // Primary Key

            // Self-referencing foreign key (Self Join)
            $table->unsignedBigInteger('parent_category_id')->nullable(); // Nullable for root categories
            $table->foreign('parent_category_id')->references('id')->on('product_categories')
                ->onUpdate('cascade')
                ->onDelete('cascade'); // Cascades deletes

            // Foreign key for brand
            $table->unsignedBigInteger('brand_id');
            $table->foreign('brand_id')->references('id')->on('brands')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->string('category_name');

            $table->nullableTimestamps(); // Includes created_at and updated_at
            $table->softDeletes(); // Enables soft delete functionality
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_categories'); // Fixed table name
    }
};
