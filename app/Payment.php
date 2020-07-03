<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    //
    protected $table    = "payments";
    protected $fillable = ['whallet_id', 'token', 'id_sesion', 'status'];

    public function wallet(){
        return $this->belongsTo('App\Wallet');
    }
}
