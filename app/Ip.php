<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ip extends Model
{
    protected $filleable = [
        'city_id',
        'ip'
    ];

    public function city(){
        return $this->belongsTo('App\IpCity', 'city_id');
    }
}
