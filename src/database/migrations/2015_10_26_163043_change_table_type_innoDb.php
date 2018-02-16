<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class ChangeTableTypeInnoDb extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		$tablesName = array (
								'arrives',
								'auctions',
								'batch_statuses',
								'batches',
								'bids',
								'boats',
								'comprador',
								'jobs',
								'migrations',
								'password_resets',
								'products',
								'users',
								'vendedor'
							);
							
		foreach($tablesName as $table)
		{
			DB::statement("ALTER TABLE $table ENGINE = InnoDB");
		}
		
        
		
		
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
