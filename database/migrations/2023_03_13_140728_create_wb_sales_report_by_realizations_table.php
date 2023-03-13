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
            $table->unsignedBigInteger('realizationreport_id');
            $table->dateTime('date_from')->index();
            $table->dateTime('date_to')->index();
            $table->dateTime('create_dt')->index();
            $table->json('suppliercontract_code')->nullable();
            $table->unsignedBigInteger('rrd_id');
            $table->unsignedBigInteger('gi_id');
            $table->string('subject_name');
            $table->unsignedBigInteger('nm_id');
            $table->string('brand_name');
            $table->string('sa_name');
            $table->string('ts_name');
            $table->string('barcode');
            $table->string('doc_type_name');
            $table->unsignedInteger('quantity');
            $table->unsignedInteger('retail_price');
            $table->unsignedInteger('retail_amount');
            $table->integer('sale_percent');
            $table->integer('commission_percent');
            $table->string('office_name');
            $table->string('supplier_oper_name');
            $table->dateTime('order_dt');
            $table->dateTime('sale_dt');
            $table->dateTime('rr_dt');
            $table->unsignedBigInteger('shk_id');
            $table->unsignedInteger('retail_price_withdisc_rub');
            $table->unsignedInteger('delivery_amount');
            $table->unsignedInteger('return_amount');
            $table->unsignedInteger('delivery_rub');
            $table->string('gi_box_type_name');
            $table->integer('product_discount_for_report');
            $table->bigInteger('supplier_promo');
            $table->bigInteger('rid');
            $table->integer('ppvz_spp_prc');
            $table->integer('ppvz_kvw_prc_base');
            $table->integer('ppvz_kvw_prc');
            $table->integer('ppvz_sales_commission');
            $table->integer('ppvz_for_pay');
            $table->integer('ppvz_reward');
            $table->integer('acquiring_fee');
            $table->string('acquiring_bank');
            $table->integer('ppvz_vw');
            $table->integer('ppvz_vw_nds');
            $table->unsignedInteger('ppvz_office_id');
            $table->string('ppvz_office_name');
            $table->unsignedInteger('ppvz_supplier_id');
            $table->string('ppvz_supplier_name');
            $table->string('ppvz_inn');
            $table->string('declaration_number');
            $table->string('bonus_type_name');
            $table->string('sticker_id');
            $table->string('site_country');
            $table->integer('penalty');
            $table->integer('additional_payment');
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