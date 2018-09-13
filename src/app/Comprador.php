<?php

namespace App;

use App\Http\Traits\priceTrait;
use Illuminate\Database\Eloquent\Model;

class Comprador extends Model
{
    use priceTrait;
    protected $table = 'comprador';
    protected $fillable = ['user_id', 'dni','bid_limit'];
    public $timestamps = false;
}
