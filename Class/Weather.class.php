<?php

Class Weather
{

   protected $configs;

   protected $locations;
   protected $stations;
   protected $datasets;
   protected $data;
   protected $datatypes;

   protected $error = [
      "status"  => 0,
      "message" => ""
   ];

   public function __construct( $configs )
   {
      $this->configs = $configs;
   }

   protected function error( $errorMessage )
   {
      $this->error = [
         "status"  => 1,
         "message" => $errorMessage
      ];
      return $this;
   }

   protected function curl( $request )
   {
      return Curl::get([
         "headers" => [
            "token" => "token: {$this->configs['token']}"
         ],
         "url"     => "{$this->configs['url']}{$request}"
      ]);
   }

   protected function locations()
   {
      if( empty( $_GET['city'] ) )
      {
         return $this->error("API city not found.");
      }
      $result = $this->curl("/locations/{$this->configs['locations'][$_GET['city']]}");
      $this->locations = [
         "city" => $result->name,
         "id"   => $result->id
      ];
      return $this;
   }

   protected function stations()
   {
      $result = $this->curl("/stations/?locationid={$this->locations['id']}");
      print_r($result);
      $this->stations = [
         "name"          => $result->results[0]->name,
         "id"            => $result->results[0]->id,
         "latitude"      => $result->results[0]->latitude,
         "longitude"     => $result->results[0]->longitude,
         "elevation"     => $result->results[0]->elevation,
         "elevationUnit" => $result->results[0]->elevationUnit
      ];
      return $this;
   }

   protected function datasets()
   {
      $result = $this->curl("/datasets");
      foreach ($result->results as $datasets) {
         $this->datasets[$datasets->id] = [
            "name" => $datasets->name,
            "uid"  => $datasets->uid,
            "id"   => $datasets->id
         ];
      }
      return $this;
   }

   protected function data()
   {
      $uri = '';
      $uri .= "?datasetid={$this->datasets['GHCND']['id']}";
      $uri .= "&startdate=2019-12-10";
      $uri .= "&enddate=2019-12-11";
      // $uri .= "&locationid={$this->locations['id']}";
      $uri .= "&stationid={$this->stations['id']}";
      $uri .= "&units=metric";

      echo $uri;
      $result = $this->curl("/data{$uri}");
      echo '<pre>';
      print_r($result);
      foreach ($result->results as $data) {
         $this->data[$data->datatype] = [
            "date"       => $data->date,
            "datatype"   => $this->datatypes( $data->datatype ),
            "station"    => $data->station,
            "attributes" => $data->attributes,
            "value"      => $data->value
         ];
      }
      return $this;
   }

   protected function datatypes($datatype)
   {
      $result = $this->curl("/datatypes/{$datatype}");
      $this->datatypes = [
         "id"   => $result->id
      ];
      return $this->datatypes;
   }

   public function set()
   {
      $this->locations();
      $this->stations();
      $this->datasets();
      $this->data();

      if( empty( $this->data ) )
      {
         return $this->error("API Data not found.");
      }

      return $this;
   }

   public function get()
   {
      if( $this->error["status"] == 1 )
      {
         return $this->error;
      }

      return $this->data;
   }

}
