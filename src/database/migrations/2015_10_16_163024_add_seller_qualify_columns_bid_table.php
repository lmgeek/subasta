<?php

use App\Bid;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSellerQualifyColumnsBidTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bids', function (Blueprint $table) {
            $table->enum('seller_calification',[Bid::CALIFICACION_POSITIVA, Bid::CALIFICACION_NEUTRAL, Bid::CALIFICACION_NEGATIVA])->nullable()->default(null);
            $table->longText('seller_calification_comments');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bids', function (Blueprint $table) {
            $table->dropColumn('seller_calification');
            $table->dropColumn('seller_calification_comments');
        });
    }
}
