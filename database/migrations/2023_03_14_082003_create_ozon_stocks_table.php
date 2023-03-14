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
            $table->string('offer_id');
            $table->unsignedBigInteger('product_id')->index();
            $table->unsignedBigInteger('present');
            $table->unsignedBigInteger('reserved');
            $table->string('type')->index();
            $table->date('created_at')->default(Carbon::today('Europe/Moscow'));
            $table->unique(['offer_id', 'created_at']);
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