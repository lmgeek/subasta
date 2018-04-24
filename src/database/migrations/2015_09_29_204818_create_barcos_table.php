<?php

use App\Boat;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBarcosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('boats', function (Blueprint $table) {
            $table->increments('id');
			$table->integer('user_id')->unsigned();
            $table->string('name');
            $table->string('matricula');
            $table->enum('status',[Boat::PENDIENTE,Boat::APROBADO,Boat::RECHAZADO]);
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
        Schema::drop('boats');
    }
}
