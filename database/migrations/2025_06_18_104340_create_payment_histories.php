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
        Schema::create('payment_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('order_id');
            $table->json('product_items')->nullable();
            $table->double('total_amount')->nullable();
            $table->double('discount_amount')->nullable();
            $table->string('promocode')->nullable();
            $table->integer('payment_gateway_id')->default('0');
            $table->string('payment_gateway_method')->default('offline');
            $table->string('payment_gateway_transaction_id')->nullable();
            $table->text('payment_transaction_details')->nullable();
            $table->tinyInteger('payment_status')->default('0')->comment('0=Pending, 1=Success, 2=Failed, 3=On progress, 4=Cancelled, 5=Refunded');
            $table->integer('cancelled_by')->nullable();
            $table->string('cancelled_by_name')->nullable();
            $table->string('cancelled_by_email')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->integer('refund_status')->nullable()->comment('0=Pending, 1=Success, 2=Failed');
            $table->integer('refund_by')->nullable();
            $table->timestamp('refund_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_histories');
    }
};
