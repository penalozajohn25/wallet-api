<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    //
    protected $table    = "wallets";
    protected $fillable = ['id_client', 'document_client', 'balance'];

    function client(){
        return $this->hasOne('App\Client');
    }

    public function payments(){
        return $this->hasMany('App\Payment');
    }
}
