# NOAA API

## Live

* Not yet.

## Built With

* PHP
* Data from [NOAA](https://www.ncdc.noaa.gov/)

## config.php
```php
return [
   "url"        => "https://www.ncdc.noaa.gov/cdo-web/api/v2",
   "token"      => "YOUR_KEY",
   "locations"  => [
      "aveiro"  => "CITY:PO000001",
      "braga"   => "CITY:PO000002",
      "coimbra" => "CITY:PO000003",
      "evora"   => "CITY:PO000004",
      "funchal" => "CITY:PO000005",
      "lisboa"  => "CITY:PO000006",
      "porto"   => "CITY:PO000007"
   ]
];

```

## Authors
* **Bruno Costa**
