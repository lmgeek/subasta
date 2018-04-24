<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BatchStatus extends Model
{
    protected $table = 'batch_statuses';

    protected $fillable = ['batch_id', 'assigned_auction','auction_sold','private_sold','remainder'];

    public function batch(){
        return $this->belongsTo('App\Batch');
    }
}
