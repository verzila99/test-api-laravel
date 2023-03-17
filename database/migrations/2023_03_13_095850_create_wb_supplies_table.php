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
            $table->id();
            $table->unsignedBigInteger('incomeId')->index();
            $table->string('number');
            $table->dateTime('date')->index();
            $table->dateTime('lastChangeDate')->index();
            $table->string('supplierArticle')->index();
            $table->string('techSize');
            $table->string('barcode');
            $table->integer('quantity');
            $table->float('totalPrice');
            $table->dateTime('dateClose')->index();
            $table->string('warehouseName')->index();
            $table->unsignedBigInteger('nmId')->index();
            $table->string('status')->index();
            $table->unique(['incomeId', 'barcode']);
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