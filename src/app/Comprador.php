<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comprador extends Model
{
    protected $table = 'comprador';
    protected $fillable = ['user_id', 'dni','bid_limit'];
    public $timestamps = false;
}
