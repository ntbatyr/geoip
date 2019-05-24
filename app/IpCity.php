<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IpCity extends Model
{
    protected $fillable = [
        'name',
        'country_id',
        'longitude',
        'latitude'
    ];

    public function country(){
        return $this->belongsTo('App\IpCountry', 'country_id');
    }

    public function ips(){
        return $this->hasMany('App\Ip', 'city_id');
    }
}
