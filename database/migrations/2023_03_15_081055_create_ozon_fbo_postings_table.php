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
            $table->string('additional_data_key')->nullable();
            $table->string('additional_data_value')->nullable();
            $table->string('city')->index()->nullable();
            $table->string('delivery_type')->nullable();
            $table->boolean('is_legal')->nullable();
            $table->boolean('is_premium')->nullable();
            $table->string('payment_type_group_name')->nullable();
            $table->string('region')->nullable();
            $table->unsignedBigInteger('warehouse_id')->index()->nullable();
            $table->string('warehouse_name')->index()->nullable();
            $table->unsignedBigInteger('cancel_reason_id')->nullable();
            $table->dateTime('created_at')->nullable();
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
            $table->string('cluster_from')->nullable();
            $table->string('cluster_to')->nullable();
            $table->json('actions')->nullable();
            $table->string('currency_code')->nullable();
            $table->string('client_price')->nullable();
            $table->double('commission_amount')->nullable();
            $table->integer('commission_percent')->nullable();
            $table->integer('commissions_currency_code')->nullable();
            $table->double('item_marketplace_service_item_deliv_to_customer')->nullable();
            $table->double('item_marketplace_service_item_direct_flow_trans')->nullable();
            $table->double('item_marketplace_service_item_dropoff_ff')->nullable();
            $table->double('item_marketplace_service_item_dropoff_pvz')->nullable();
            $table->double('item_marketplace_service_item_dropoff_sc')->nullable();
            $table->double('item_marketplace_service_item_fulfillment')->nullable();
            $table->double('item_marketplace_service_item_pickup')->nullable();
            $table->double('item_marketplace_service_item_return_after_deliv_to_customer')->nullable();
            $table->double('item_marketplace_service_item_return_flow_trans')->nullable();
            $table->double('item_marketplace_service_item_return_not_deliv_to_customer')->nullable();
            $table->double('item_marketplace_service_item_return_part_goods_customer')->nullable();
            $table->double('old_price')->nullable();
            $table->double('payout')->nullable();
            $table->json('picking')->nullable();
            $table->double('financial_data_products_price')->nullable();
            $table->unsignedBigInteger('financial_data_products_product_id')->nullable();
            $table->unsignedBigInteger('financial_data_products_quantity')->nullable();
            $table->double('total_discount_percent')->nullable();
            $table->double('total_discount_value')->nullable();
            $table->dateTime('in_process_at')->nullable();
            $table->unsignedBigInteger('order_id');
            $table->string('order_number')->nullable();
            $table->string('posting_number');
            $table->string('products_digital_code')->nullable();
            $table->string('products_name')->nullable();
            $table->string('products_offer_id')->index();
            $table->string('products_currency_code')->nullable();
            $table->string('products_price')->nullable();
            $table->string('products_quantity')->nullable();
            $table->string('products_sku');
            $table->string('status')->nullable();
            $table->unique(['order_id', 'posting_number', 'products_sku']);
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