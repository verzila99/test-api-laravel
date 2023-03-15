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
            $table->string('gNumber')->index();
            $table->dateTime('date')->index();
            $table->dateTime('lastChangeDate')->index();
            $table->string('supplierArticle')->index();
            $table->string('techSize');
            $table->string('barcode');
            $table->bigInteger('totalPrice');
            $table->bigInteger('discountPercent');
            $table->string('warehouseName')->index();
            $table->string('oblast')->index();
            $table->unsignedBigInteger('incomeID')->index();
            $table->unsignedBigInteger('odid');
            $table->unsignedBigInteger('nmId')->index();
            $table->string('subject')->index();
            $table->string('category')->index();
            $table->string('brand')->index();
            $table->boolean('isCancel')->index();
            $table->dateTime('cancel_dt')->index();
            $table->string('sticker');
            $table->string('srid');
            $table->unique(['odid']);
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