<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ozon_fbo_postings', function (Blueprint $table) {
            $table->id();
            $table->json('additional_data')->nullable();
            $table->string('city')->nullable();
            $table->string('delivery_type')->nullable();
            $table->boolean('is_legal')->nullable();
            $table->boolean('is_premium')->nullable();
            $table->string('payment_type_group_name')->nullable();
            $table->string('region')->nullable();
            $table->unsignedBigInteger('warehouse_id')->nullable();
            $table->string('warehouse_name')->nullable();
            $table->integer('cancel_reason_id')->nullable();
            $table->dateTime('posting_created_at')->nullable();
            $table->double('marketplace_service_item_deliv_to_customer')->nullable();
            $table->double('marketplace_service_item_direct_flow_trans')->nullable();
            $table->double('marketplace_service_item_dropoff_ff')->nullable();
            $table->double('marketplace_service_item_dropoff_pvz')->nullable();
            $table->double('marketplace_service_item_dropoff_sc')->nullable();
            $table->double('marketplace_service_item_fulfillment')->nullable();
            $table->double('marketplace_service_item_pickup')->nullable();
            $table->double('marketplace_service_item_return_after_deliv_to_customer')->nullable();
            $table->double('marketplace_service_item_return_flow_trans')->nullable();
            $table->double('marketplace_service_item_return_not_deliv_to_customer')->nullable();
            $table->double('marketplace_service_item_return_part_goods_customer')->nullable();
            $table->string('currency_code')->nullable();
            $table->double('commission_amount')->nullable();
            $table->integer('commission_percent')->nullable();
            $table->double('payout')->nullable();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->double('old_price')->nullable();
            $table->double('total_discount_value')->nullable();
            $table->double('total_discount_percent')->nullable();
            $table->json('actions')->nullable();
            $table->json('picking')->nullable();
            $table->string('client_price')->nullable();
            $table->string('cluster_from')->nullable();
            $table->string('cluster_to')->nullable();
            $table->dateTime('in_process_at')->nullable();
            $table->unsignedBigInteger('order_id');
            $table->string('order_number')->nullable();
            $table->string('posting_number');
            $table->json('digital_codes')->nullable();
            $table->string('name')->nullable();
            $table->string('offer_id')->nullable();
            $table->float('price')->nullable();
            $table->integer('quantity')->nullable();
            $table->unsignedBigInteger('sku');
            $table->string('status')->nullable();
            $table->timestamps();
            $table->unique(['order_id', 'posting_number', 'sku'], 'ozon_posting_fbo_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ozon_posting_fbos');
    }
};