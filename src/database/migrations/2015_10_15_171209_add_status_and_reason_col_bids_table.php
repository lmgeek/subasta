<?php

use App\Bid;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatusAndReasonColBidsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bids', function (Blueprint $table) {
            $table->enum('status',[Bid::NO_CONCRETADA, Bid::CONCRETADA, Bid::PENDIENTE])->default(Bid::PENDIENTE);
            $table->longText('reason');
            $table->enum('user_calification',[Bid::CALIFICACION_POSITIVA, Bid::CALIFICACION_NEUTRAL, Bid::CALIFICACION_NEGATIVA])->nullable()->default(null);
            $table->longText('user_calification_comments');
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
            $table->dropColumn('status');
            $table->dropColumn('reason');
            $table->dropColumn('user_calification');
            $table->dropColumn('user_calification_comments');
        });
    }
}
