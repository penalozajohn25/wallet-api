<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    //
    protected $table    = "clients";
    protected $fillable = ['document', 'name', 'email', 'phone'];

    function wallet(){
        return $this->hasOne('App\Wallet');
    }

}
