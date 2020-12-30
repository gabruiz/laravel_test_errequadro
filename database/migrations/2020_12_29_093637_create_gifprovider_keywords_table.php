<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGifproviderKeywordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gifprovider_keywords', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('keyword_id')->unsigned();
            $table->bigInteger('gifprovider_id')->unsigned();
            $table->timestamps();
            $table->foreign('keyword_id')->references('id')->on('keywords')->onDelete('cascade');
            $table->foreign('gifprovider_id')->references('id')->on('gifproviders')->onDelete('cascade');
            $table->integer('counter');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gifprovider_keywords');
    }
}
