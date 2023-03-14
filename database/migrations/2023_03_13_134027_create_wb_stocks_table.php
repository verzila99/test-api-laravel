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
            $table->unsignedBigInteger('quantity');
            $table->boolean('isSupply');
            $table->boolean('isRealization');
            $table->unsignedBigInteger('quantityFull');
            $table->string('warehouseName')->index();
            $table->bigInteger('nmId');
            $table->string('subject')->index();
            $table->string('category')->index();
            $table->integer('daysOnSite');
            $table->string('brand')->index();
            $table->string('SCCode');
            $table->bigInteger('Price');
            $table->bigInteger('Discount');
            $table->timestamp('created_at')->useCurrent()->index();
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