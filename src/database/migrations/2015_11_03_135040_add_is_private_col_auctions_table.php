<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Auction;
class AddIsPrivateColAuctionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('auctions', function (Blueprint $table) {
			$table->enum('type',[Auction::AUCTION_PUBLIC, Auction::AUCTION_PRIVATE])->nullable()->default(Auction::AUCTION_PUBLIC)->after('active');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('auctions', function (Blueprint $table) {
            	$table->dropColumn('type');
        });
    }
}
