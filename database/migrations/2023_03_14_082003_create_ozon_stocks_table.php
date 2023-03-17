<?php

use Carbon\Carbon;
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
            $table->id();
            $table->string('offer_id')->index();
            $table->unsignedBigInteger('product_id')->index();
            $table->integer('fbo_present')->nullable();
            $table->integer('fbo_reserved')->nullable();
            $table->integer('fbs_present')->nullable();
            $table->integer('fbs_reserved')->nullable();
            $table->date('date')->index();
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