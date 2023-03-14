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
        Schema::create('wb_sales_report_by_realizations', function (Blueprint $table) {
            $table->unsignedBigInteger('realizationreport_id')->index();
            $table->dateTime('date_from')->index();
            $table->dateTime('date_to')->index();
            $table->dateTime('create_dt')->index();
            $table->json('suppliercontract_code')->nullable();
            $table->unsignedBigInteger('rrd_id')->index();
            $table->unsignedBigInteger('gi_id')->index();
            $table->string('subject_name');
            $table->unsignedBigInteger('nm_id')->index();
            $table->string('brand_name');
            $table->string('sa_name');
            $table->string('ts_name');
            $table->string('barcode');
            $table->string('doc_type_name');
            $table->unsignedInteger('quantity');
            $table->unsignedBigInteger('retail_price');
            $table->unsignedInteger('retail_amount');
            $table->integer('sale_percent');
            $table->integer('commission_percent');
            $table->string('office_name')->index();
            $table->string('supplier_oper_name')->index();
            $table->dateTime('order_dt')->index();
            $table->dateTime('sale_dt')->index();
            $table->dateTime('rr_dt')->index();
            $table->unsignedBigInteger('shk_id');
            $table->unsignedBigInteger('retail_price_withdisc_rub');
            $table->unsignedInteger('delivery_amount');
            $table->unsignedInteger('return_amount');
            $table->unsignedBigInteger('delivery_rub');
            $table->string('gi_box_type_name');
            $table->integer('product_discount_for_report');
            $table->bigInteger('supplier_promo');
            $table->bigInteger('rid')->index();
            $table->bigInteger('ppvz_spp_prc');
            $table->bigInteger('ppvz_kvw_prc_base');
            $table->bigInteger('ppvz_kvw_prc');
            $table->bigInteger('ppvz_sales_commission');
            $table->bigInteger('ppvz_for_pay');
            $table->bigInteger('ppvz_reward');
            $table->bigInteger('acquiring_fee');
            $table->string('acquiring_bank');
            $table->bigInteger('ppvz_vw');
            $table->bigInteger('ppvz_vw_nds');
            $table->unsignedBigInteger('ppvz_office_id')->index();
            $table->string('ppvz_office_name')->index();
            $table->unsignedBigInteger('ppvz_supplier_id')->index();
            $table->string('ppvz_supplier_name')->index();
            $table->string('ppvz_inn');
            $table->string('declaration_number');
            $table->string('bonus_type_name');
            $table->string('sticker_id')->index();
            $table->string('site_country')->index();
            $table->bigInteger('penalty');
            $table->bigInteger('additional_payment');
            $table->string('kiz');
            $table->string('srid');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wb_sales_report_by_realizations');
    }
};