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
      $string = urlencode($string);
      $full_url = $this->baseUrl . $MikudAction . $string;
      $result = $this->download_page($full_url);
      return $result;
  }

  function GetZipCodeByAddress($city, $street, $house, $entrance = null){
    $url_string = 'SearchZip?OpenAgent&';
    $data = $this->DownloadString($url_string, "Location=".$city."&Street=".$street."&House=".$house."&Entrance=".$entrance);
    return $this->GetZipCode($data);
  }

  function CityAutoComplete($startsWith){
    $url_string = 'CreateLocationsforAutoComplete?OpenAgent&';
    $data = $this->DownloadString($url_string, "StartsWith=".$startsWith);

    $data = str_replace(array( '(', ')' ), '', $data);
    $data = substr($data, 0, -2);

    //$data = '['.$data.']';

    echo $data;

    return $data;
  }

  function StreetAutoComplete($startsWith, $location){
    $url_string = 'CreateStreetsforAutoComplete?OpenAgent&';
    $data = $this->DownloadString($url_string, "StartsWith=".$startsWith."&Location=".$location);

    $data = str_replace(array( '(', ')' ), '', $data);
    $data = substr($data, 0, -2);

    //$data = '['.$data.']';

    echo $data;
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
      //var match = Regex.Match($data, @"RES[0-9]*\d");
      //var result = match.Value.Substring(4);

      //preg_match('RES[0-9]*\d', $data, $match);
      //$status = substr($match ,4);

      $data = strip_tags($data);

      function clean($string) {
         $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.

         return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
      }

      $data = clean($data);

      //$data = preg_replace("/[^a-zA-Z0-9]+/", "", $data);
      $result = substr($data ,-7);

      preg_match('/RES[0-9]*/', $data, $match);
      $match = $match[0];
      $status = substr($match ,-2);


      /*ZipCode zipCode = new ZipCode()
      {
          Status = true,
          Value = result
      };*/

      if ($status == "11")
      {
          $result = false;
          $massage = "לא נמצא מיקוד מתאים. במידה והוזנה כניסה, יש לנסות לחפש בלעדיה…";
      }
      else if ($status == "12" || $match == "RES2")
      {
          $result = false;
          $massage = "לא נמצא מיקוד מתאים. יש לנסות שנית עם רחוב ו/או מספר בית…";
      }
      else if ($status == "13" || $match == "RES013")
      {
          $result = false;
          $massage = "לא נמצא מיקוד מתאים עם העיר ו/או הרחוב שהוזנ/ה. יש לנסות שנית…";
      }
      else if ($match == "RES5")
      {
          $result = false;
          $massage = "לא נמצא מיקוד";
      }
      else if ($status == "")
      {
          $result = false;
          $massage = 'Error';
      }

      if(!$result){
        echo 0;
      }

      echo $result;
      
  }
}



