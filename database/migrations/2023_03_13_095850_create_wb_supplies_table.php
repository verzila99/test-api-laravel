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
        Schema::create('wb_supplies', function (Blueprint $table) {
            $table->integer('incomeId');
            $table->string('number');
            $table->string('date');
            $table->string('lastChangeDate');
            $table->string('supplierArticle');
            $table->string('techSize');
            $table->string('barcode');
            $table->integer('quantity');
            $table->integer('totalPrice');
            $table->string('dateClose');
            $table->string('warehouseName');
            $table->integer('nmId');
            $table->string('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wb_supplies');
    }
};