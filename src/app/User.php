<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends Model implements AuthenticatableContract,
                                    AuthorizableContract,
                                    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword;

    const INTERNAL = "internal";
    const VENDEDOR = "seller";
    const COMPRADOR = "buyer";
    const BROKER = "broker";

    const PENDIENTE = "pending";
    const APROBADO = "approved";
    const RECHAZADO = "rejected";

    protected  $info = null;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'email', 'password','phone','type','status','rebound'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    /**
     * User constructor.
     * @param $info
     */

    public function client()
    {
        return $this->belongsTo('App\Client');
    }

    public function seller()
    {
        return $this->hasOne('App\Vendedor');
    }
	
	public function buyer()
    {
        return $this->hasOne('App\Comprador');
    }

    public function info()
    {
        return ($this->info===null)?new \stdClass():$this->info;
    }

    public function setInfo($value){
        $this->info = $value;
    }
	
	public function boat()
	{	
		return $this->hasMany('App\Boat');
	}

    public function subscription()
    {
        return $this->hasMany('App\Subscription');
    }

    public function bids()
    {
        return $this->hasMany('App\Bid');
    }
	
	public function rating(){
        return $this->hasOne('App\UserRating');
    }

    public static function filter($name = null, $type = null, $status = null)
    {
        $rtrn = User::where('type','<>',User::INTERNAL);
        if(!is_null($name) and $name != '') $rtrn->where('name','like',"%$name%");
        if(!is_null($type) and count($type) > 0) $rtrn->whereIn('type',$type);
        if(!is_null($status) and count($status) > 0) $rtrn->whereIn('status',$status);

        return $rtrn->orderBy('created_at','desc')->get();
    }
	
	public static function getInternals($status = null)
    {
        $rtrn = User::where('type','=',User::INTERNAL);
        if(!is_null($status) and count($status) > 0) $rtrn->whereIn('status',$status);

        return $rtrn->orderBy('created_at','desc')->get();
    }

    public function is($type)
    {
        return ($this->type == $type);
    }

    public function isAdmin()
    {
        return $this->is(User::INTERNAL);
    }

    public function isSeller()
    {
        return $this->is(User::VENDEDOR);
    }

    public function isBuyer()
    {
        return $this->is(User::COMPRADOR);
    }

    public function isApproved()
    {
        return ($this->status == User::APROBADO);
    }

    public function isPending()
    {
        return ($this->status == User::PENDIENTE);
    }

    public function isRejected()
    {
        return ($this->status == User::RECHAZADO);
    }

    public function isSuscribe(Auction $auction)
    {
        $rtrn = false;
        if(count($this->subscription)>0){
            foreach ($this->subscription as $sub) {
                if(!is_null($sub->auction) and $sub->auction->id == $auction->id){
                    $rtrn = true;
                    break;
                }
            }
        }

        return $rtrn;
    }
}