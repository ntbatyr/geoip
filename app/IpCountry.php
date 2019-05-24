<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IpCountry extends Model
{
    protected $fillable = [
        'name',
        'code'
    ];

    public function cities(){
        return $this->hasMany('App\IpCity', 'country_id');
    }
}
