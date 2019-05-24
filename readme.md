## GoeIP Microservice

## Usage

Microservice returns clients geo-location by IP address.
Response format JSON/

GET / http://a0293490.xsph.ru/ip2geo?ip={ip_address}

## Example

http://a0293490.xsph.ru/ip2geo?ip=1.1.1.1


<pre>
{
    "code": 200,
    "country_name": "Australia",
    "city_name": "Canberra",
    "longitude": 143.2104,
    "latitude": -33.494
}
</pre 
