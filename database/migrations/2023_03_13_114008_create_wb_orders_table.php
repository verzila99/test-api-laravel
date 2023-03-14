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
        Schema::create('wb_orders', function (Blueprint $table) {
            $table->string('gNumber');
            $table->dateTime('date');
            $table->dateTime('lastChangeDate');
            $table->string('supplierArticle');
            $table->string('techSize');
            $table->string('barcode');
            $table->bigInteger('totalPrice');
            $table->bigInteger('discountPercent');
            $table->string('warehouseName');
            $table->string('oblast');
            $table->unsignedBigInteger('incomeID');
            $table->unsignedBigInteger('odid');
            $table->unsignedBigInteger('nmId');
            $table->string('subject');
            $table->string('category');
            $table->string('brand');
            $table->boolean('isCancel');
            $table->dateTime('cancel_dt');
            $table->string('sticker');
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
        Schema::dropIfExists('wb_orders');
    }
};