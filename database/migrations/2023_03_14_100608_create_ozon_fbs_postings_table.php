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
        Schema::create('ozon_fbs_postings', function (Blueprint $table) {
            $table->id();
            $table->json('addressee')->nullable();
            $table->string('city')->nullable();
            $table->dateTime('delivery_date_begin')->nullable();
            $table->dateTime('delivery_date_end')->nullable();
            $table->string('delivery_type')->nullable();
            $table->boolean('is_legal')->nullable();
            $table->boolean('is_premium')->nullable();
            $table->string('payment_type_group_name')->nullable();
            $table->string('region')->nullable();
            $table->string('tpl_provider')->nullable();
            $table->unsignedBigInteger('tpl_provider_id')->nullable();
            $table->json('barcodes')->nullable();
            $table->boolean('affect_cancellation_rating')->nullable();
            $table->string('cancel_reason')->nullable();
            $table->unsignedBigInteger('cancel_reason_id')->nullable();
            $table->string('cancellation_initiator')->nullable();
            $table->string('cancellation_type')->nullable();
            $table->boolean('cancelled_after_ship')->nullable();
            $table->json('customer')->nullable();
            $table->dateTime('delivering_date')->nullable();
            $table->unsignedBigInteger('delivery_method_id')->nullable();
            $table->string('delivery_method_name')->nullable();
            $table->string('warehouse')->nullable();
            $table->unsignedBigInteger('warehouse_id')->nullable();
            $table->string('cluster_from')->nullable();
            $table->string('cluster_to')->nullable();
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
            $table->string('client_price')->nullable();
            $table->json('actions')->nullable();
            $table->double('commission_amount')->nullable();
            $table->integer('commission_percent')->nullable();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->double('old_price')->nullable();
            $table->double('payout')->nullable();
            $table->json('picking')->nullable();
            $table->double('total_discount_percent')->nullable();
            $table->double('total_discount_value')->nullable();
            $table->dateTime('in_process_at')->nullable();
            $table->boolean('is_express')->nullable();
            $table->boolean('is_multibox')->nullable();
            $table->integer('multi_box_qty')->nullable();
            $table->unsignedBigInteger('order_id');
            $table->string('order_number')->nullable();
            $table->string('parent_posting_number')->nullable();
            $table->string('posting_number');
            $table->json('mandatory_mark')->nullable();
            $table->string('name')->nullable();
            $table->string('offer_id')->nullable();
            $table->string('currency_code')->nullable();
            $table->double('price')->nullable();
            $table->integer('quantity')->nullable();
            $table->unsignedBigInteger('sku');
            $table->json('products_requiring_gtd')->nullable();
            $table->json('products_requiring_country')->nullable();
            $table->json('products_requiring_mandatory_mark')->nullable();
            $table->json('products_requiring_rnpt')->nullable();
            $table->dateTime('shipment_date')->nullable();
            $table->string('status')->nullable();
            $table->string('tpl_integration_type')->nullable();
            $table->string('tracking_number')->nullable();
            $table->json('available_actions')->nullable();
            $table->timestamps();
            $table->unique(['order_id', 'posting_number', 'sku']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ozon_postings');
    }
};