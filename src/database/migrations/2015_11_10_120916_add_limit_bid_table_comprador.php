<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLimitBidTableComprador extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('comprador', function ($table) {
			$table->string('bid_limit')->default(env('BID_LIMIT'));
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('comprador', function ($table) {
			$table->dropColumn('bid_limit');
		});
    }
}
