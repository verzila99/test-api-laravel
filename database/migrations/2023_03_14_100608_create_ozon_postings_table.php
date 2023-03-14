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
        Schema::create('ozon_postings', function (Blueprint $table) {
            $table->string('addressee_name')->nullable();
            $table->string('addressee_phone')->nullable();
            $table->string('city');
            $table->dateTime('delivery_date_begin')->index();
            $table->dateTime('delivery_date_end')->index();
            $table->string('delivery_type');
            $table->boolean('is_legal');
            $table->boolean('is_premium');
            $table->string('payment_type_group_name');
            $table->string('region');
            $table->string('tpl_provider');
            $table->unsignedBigInteger('tpl_provider_id');
            $table->string('warehouse')->index();
            $table->unsignedBigInteger('warehouse_id')->index();
            $table->string('lower_barcode');
            $table->string('upper_barcode');
            $table->boolean('affect_cancellation_rating');
            $table->unsignedBigInteger('cancel_reason_id');
            $table->string('cancellation_initiator');
            $table->string('cancellation_type');
            $table->boolean('cancelled_after_ship');
            $table->json('customer_address')->nullable();
            $table->string('customer_email')->nullable()->index();
            $table->unsignedBigInteger('customer_id')->nullable()->index();
            $table->string('customer_name')->nullable();
            $table->string('customer_phone')->nullable();
            $table->dateTime('delivering_date')->nullable();
            $table->unsignedBigInteger('delivery_method_id');
            $table->string('delivery_method_name');
            $table->string('delivery_method_tpl_provider');
            $table->unsignedBigInteger('delivery_method_tpl_provider_id');
            $table->string('delivery_method_warehouse');
            $table->unsignedBigInteger('delivery_method_warehouse_id');
            $table->string('financial_data_cluster_from');
            $table->string('financial_data_cluster_to');
            $table->json('financial_data_posting_services');
            $table->json('financial_data_products');
            $table->dateTime('in_process_at');
            $table->boolean('is_express');
            $table->boolean('is_multibox');
            $table->unsignedBigInteger('multi_box_qty');
            $table->unsignedBigInteger('order_id');
            $table->string('order_number');
            $table->string('parent_posting_number');
            $table->json('products');
            $table->json('requirements');
            $table->dateTime('shipment_date')->index();
            $table->string('status')->index();
            $table->string('tpl_integration_type');
            $table->string('tracking_number');

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