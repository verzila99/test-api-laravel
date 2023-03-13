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
        Schema::create('wb_prices', function (Blueprint $table) {
            $table->unsignedBigInteger('nmId')->index();
            $table->unsignedInteger('price')->index();
            $table->unsignedInteger('discount');
            $table->unsignedBigInteger('promoCode')->index();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wb_prices');
    }
};