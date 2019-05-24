<?php

namespace App\Http\Controllers;

use App\Ip;
use App\IpCity;
use App\IpCountry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Input;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class IpController extends Controller
{

    public function ipToGeo(){

        $data = Input::all();

        /*
         * Check if cache has an ip.
         * */

        if(Cache::has($data['ip'])){
            $result = Cache::get($data['ip']);

            return json_encode($result);
        }

        /*
         * Check if ip exists in database
         * */

        $ip = Ip::where('ip', $data['ip'])->first();

        if(empty($ip)){
            $response = json_decode(file_get_contents("http://api.ipstack.com/{$data['ip']}?access_key=e9ef3709c232dbd590330e8d3acd10c4"), true);

            if($response['type'] == null){
                return json_encode([
                    'code' => 404
                ]);
            }


            $city = IpCity::where('name', $response['region_name'])->first();
            $country = IpCountry::where('name', $response['country_name'])->first();

            if(empty($city)){

                if(empty($country)){
                    $country = new IpCountry();

                    $country->name = $response['country_name'];
                    $country->code = $response['country_code'];

                    $country->save();

                }

                $city = new IpCity();

                $city->country_id = $country->id;
                $city->name = $response['region_name'] == '' ? $response['location']['capital'] : $response['region_name'];
                $city->longitude = $response['longitude'];
                $city->latitude = $response['latitude'];

                $city->save();
            }

            $ip = new Ip();

            $ip->city_id = $city->id;
            $ip->ip = $data['ip'];

            $ip->save();

            $result = [
                'code' => 200,
                'country_name' => $response['country_name'],
                'city_name' => $response['region_name'] == '' ? $response['location']['capital'] : $response['region_name'],
                'longitude' => $response['longitude'],
                'latitude' => $response['latitude']
            ];
        } else {
            $result = [
                'code' => 200,
                'country_name' => $ip->city->name,
                'city_name' => $ip->city->country->name,
                'longitude' => $ip->city->longitude,
                'latitude' => $ip->city->latitude
            ];
        }

        Cache::put($ip->ip, $result, now()->addMinutes(30));

        return json_encode($result);
    }
}
