<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuctionOffersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('bids', function (Blueprint $table) {
           $table->increments('id');
           $table->integer('auction_id')->unsigned();
           $table->integer('user_id')->unsigned();
           $table->integer('amount');
           $table->float('price');
           $table->enum('status', ['accepted', 'pending','rejected'])->default('pending');
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
        Schema::drop('auctions_offers');
    }
}
