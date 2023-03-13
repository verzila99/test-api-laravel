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
        Schema::create('wb_sales', function (Blueprint $table) {
            $table->string('gNumber')->index();
            $table->dateTime('date')->index();
            $table->dateTime('lastChangeDate')->index();
            $table->string('supplierArticle')->index();
            $table->string('techSize');
            $table->string('barcode');
            $table->integer('totalPrice');
            $table->integer('discountPercent');
            $table->boolean('isSupply');
            $table->boolean('isRealization');
            $table->integer('promoCodeDiscount');
            $table->string('warehouseName')->index();
            $table->string('countryName')->index();
            $table->string('oblastOkrugName');
            $table->string('regionName');
            $table->integer('incomeID')->index();
            $table->string('saleID')->index();
            $table->string('odid')->index();
            $table->integer('spp');
            $table->integer('forPay');
            $table->integer('finishedPrice');
            $table->integer('priceWithDisc');
            $table->integer('nmId')->index();
            $table->string('subject');
            $table->string('category')->index();
            $table->string('brand')->index();
            $table->integer('isStorno');
            $table->string('sticker');
            $table->string('srid')->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wb_sales');
    }
};