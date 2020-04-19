<?php

Class Curl {

   public static function get( $data ){

      $ch = curl_init();

      curl_setopt($ch, CURLOPT_HTTPHEADER, $data['headers']);
      curl_setopt($ch, CURLOPT_URL, $data['url']);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

      $result = curl_exec($ch);

      curl_close($ch);

      return json_decode($result);

   }

}
