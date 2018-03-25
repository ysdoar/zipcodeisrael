<?php
class zipCode 
{

  function __construct()
  {
    $this->baseUrl = "http://www.israelpost.co.il/zip_data.nsf/";
  }

 function download_page($path) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $path);
    curl_setopt($ch, CURLOPT_FAILONERROR, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
  }

  function DownloadString($MikudAction, $string)
  {
      $full_url = $this->baseUrl . $MikudAction . $string;
      $full_url = str_replace('+', '%20', $full_url);
      $result = $this->download_page($full_url);
      return $result;
  }

  function GetZipCodeByAddress($city, $street, $house, $entrance = null){
    $url_string = 'SearchZip?OpenAgent&';
    $city = urlencode($city);
    $street = urlencode($street);
    $house = urlencode($house);
    $entrance = urlencode($entrance);
    $data = $this->DownloadString($url_string, "Location=".$city."&Street=".$street."&House=".$house);
    return $this->GetZipCode($data);
  }

  function CityAutoComplete($startsWith){
    $startsWith = urlencode($startsWith);
    $url_string = 'CreateLocationsforAutoComplete?OpenAgent&';
    $data = $this->DownloadString($url_string, "StartsWith=".$startsWith);
    $data = str_replace(array( '(', ')' ), '', $data);
    $data = substr($data, 0, -2);
    return $data;
  }

  function StreetAutoComplete($startsWith, $location){
    $url_string = 'CreateStreetsforAutoComplete?OpenAgent&';
    $location = urlencode($location);
    $startsWith = urlencode($startsWith);
    $data = $this->DownloadString($url_string, "StartsWith=".$startsWith."&Location=".$location);
    $data = str_replace(array( '(', ')' ), '', $data);
    $data = substr($data, 0, -2);
    return $data;
  }

  function GetZipCodeByPOB($city, $pob){
    $url_string = 'SearchZip?OpenAgent&';
    $data = $this->DownloadString($url_string, "Location=".$city."&POB=".$pob);
    return $this->GetZipCode($data);
  }

  function GetAddressByZipCode($zipCode){
    $url_string = 'SearchAddress7?OpenAgent&';
    $data = $this->DownloadString($url_string, "Zip=".$zipCode);
    return $data;
  }

  function GetZipCode($data)
  {
      $data = strip_tags($data);

      function clean($string) {
         $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.

         return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
      }

      $data = clean($data);
      $data = preg_replace("/[^a-zA-Z0-9]+/", "", $data);
      $result = substr($data ,-7);
      if(!$result){
        echo 0;
      }
      echo $result; 
      
  }
}



