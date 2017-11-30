# zipcodeisrael
Find zipcode by address at israel

### Class init
we need to accept the datd from Windows-1255 charset
header('Content-Type: text/html; charset=Windows-1255');

init the class
$zipcode = new zipCode;

### Methods

Get zip code from address:
$zipcode->GetZipCodeByAddress($city, $address, $home, $enter);
  
Get street auto complete:
$zipcode->StreetAutoComplete($address, $city);

Get city auto complete:
$zipcode->CityAutoComplete($city);

Get address from zipcode:
$zipcode->GetAddressByZipCode($zipCode);

Get zipcode from pob:
$zipcode->GetZipCodeByPOB($city, $pob);
