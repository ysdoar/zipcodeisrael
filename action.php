<?php
ini_set('display_errors', 1);
header('Content-Type: text/html; charset=Windows-1255');

include 'zipcode.php';

$zipcode = new zipCode;

$city = '';
$address = '';
$home = '';
$enter = '';

$action = $_GET['action'];

if(isset($_POST['city'])){
  $city = $_POST['city'];
}
if(isset($_POST['address'])){
  $address = $_POST['address'];
}
if(isset($_POST['home'])){
  $home = $_POST['home'];
}
if(isset($_POST['enter'])){
  $enter = $_POST['enter'];
}

if($action == 'get_zip'){
  $zipcode->GetZipCodeByAddress($city, $address, $home, $enter);
}else if($action == 'autocompleteAdress'){
  $zipcode->StreetAutoComplete($address, $city);
}else if($action == 'autocompleteCity'){
  $zipcode->CityAutoComplete($city);
}else if($action == 'autocompleteAdress'){
  $zipcode->CityAutoComplete('הק');
}


