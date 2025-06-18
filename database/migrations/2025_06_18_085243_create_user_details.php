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
        Schema::create('user_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('image')->nullable();
            $table->string('phone')->nullable();
            $table->timestamp('last_login')->nullable();
            $table->tinyInteger('is_phone_verified')->default('0');
            $table->tinyInteger('is_email_send')->default('0');
            $table->string('billing_country_code')->nullable();
            $table->string('billing_country')->nullable();
            $table->string('billing_address')->nullable();
            $table->string('billing_address_optional')->nullable();
            $table->string('billing_town_city')->nullable();
            $table->string('billing_state')->nullable();
            $table->string('billing_postcode')->nullable();
            $table->string('billing_landmarks')->nullable();
            $table->string('shipping_country_code')->nullable();
            $table->string('shipping_country')->nullable();
            $table->string('shipping_address')->nullable();
            $table->string('shipping_address_optional')->nullable();
            $table->string('shipping_town_city')->nullable();
            $table->string('shipping_state')->nullable();
            $table->string('shipping_postcode')->nullable();
            $table->string('shipping_landmarks')->nullable();
            $table->tinyInteger('cookie_consent_status')->default('0');
            $table->tinyInteger('is_email_newsletter_subscribed')->default('0');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_details');
    }
};
