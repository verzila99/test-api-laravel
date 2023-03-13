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
        Schema::create('wb_stocks', function (Blueprint $table) {
            $table->dateTime('lastChangeDate')->index();
            $table->string('supplierArticle')->index();
            $table->string('techSize');
            $table->string('barcode')->index();
            $table->integer('quantity');
            $table->boolean('isSupply');
            $table->boolean('isRealization');
            $table->integer('quantityFull');
            $table->string('warehouseName')->index();
            $table->integer('nmId');
            $table->string('subject')->index();
            $table->string('category')->index();
            $table->integer('daysOnSite');
            $table->string('brand')->index();
            $table->string('SCCode');
            $table->integer('Price');
            $table->integer('Discount');
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
        Schema::dropIfExists('wb_stocks');
    }
};