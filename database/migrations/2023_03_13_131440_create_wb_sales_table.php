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
            $table->id();
            $table->string('gNumber')->index();
            $table->dateTime('date')->index();
            $table->dateTime('lastChangeDate')->index();
            $table->string('supplierArticle')->index();
            $table->string('techSize');
            $table->string('barcode');
            $table->float('totalPrice');
            $table->integer('discountPercent');
            $table->boolean('isSupply');
            $table->boolean('isRealization');
            $table->float('promoCodeDiscount');
            $table->string('warehouseName')->index();
            $table->string('countryName')->index();
            $table->string('oblastOkrugName');
            $table->string('regionName');
            $table->bigInteger('incomeID')->index();
            $table->string('saleID');
            $table->unsignedBigInteger('odid')->index();
            $table->float('spp');
            $table->float('forPay');
            $table->float('finishedPrice');
            $table->float('priceWithDisc');
            $table->bigInteger('nmId')->index();
            $table->string('subject');
            $table->string('category')->index();
            $table->string('brand')->index();
            $table->integer('isStorno');
            $table->string('sticker');
            $table->string('srid')->index();
            $table->unique(['saleID']);
            $table->timestamps();
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