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
        Schema::create('ozon_stocks', function (Blueprint $table) {
            $table->string('offer_id')->index();
            $table->unsignedBigInteger('product_id')->index();
            $table->unsignedBigInteger('present');
            $table->unsignedBigInteger('reserved');
            $table->string('type')->index();
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
        Schema::dropIfExists('ozon_stocks');
    }
};