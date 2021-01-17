<?php
include('../qrcode/phpqrcode/qrlib.php');

//variable for get tokenlogin

function teste(){
  $mail = 'adduniti@adduniti.online';
  $mpdtoken = '#Adduniti2020';
  $sha1password = sha1($mpdtoken);
  $sha256chaine = $mail . 'Uniti$ELAZERTY' . $sha1password;
  $sha256password = hash("sha256", $sha256chaine);
  
  //--Call to api externallogin ---
  $curl = curl_init();
  $passwordsha = $sha1password;
  $key = $sha256password;
  $data = array("mail" => "$mail", "passwordsha" => "$passwordsha", "key" => "$key");
  $data_token = json_encode($data);
  
  //echo "$data_token";
  curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://www.adduniti.online:9303/UNITI_API_PAY_1.DLL/externallogin',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_SSL_VERIFYPEER => 0,
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS => $data_token,
    CURLOPT_HTTPHEADER => array('Content-Type: application/json')
  ));
  
  $response = curl_exec($curl);
  curl_close($curl);
  $externallogin = json_decode($response, true);
  $tokenpin = $externallogin['token'];
  
  //--CALL TO API EXTERNALLOGIN ---
  
  //variable for get externalloginpin
  $mpd = '785632';
  $pinsha = sha1($mpd);
  $secretkey = "AZERTY";
  $key = 'UnitiC$'.$pinsha.$tokenpin.$secretkey;
  $sha256password = hash("sha256", $key);
  $datapin = array("token" => "$tokenpin", "pinsha" => "$pinsha", "key" => "$sha256password");
  $data_loginpin = json_encode($datapin);
  
  $curl = curl_init();
  curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://www.adduniti.online:9303/UNITI_API_PAY_1.DLL/externalloginpin',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_SSL_VERIFYPEER => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS => $data_loginpin,
    CURLOPT_HTTPHEADER => array(
      'Content-Type: application/json'
    ),
  ));
  $response = curl_exec($curl);
  curl_close($curl);
  $externalloginpin = json_decode($response, true);
  $tokenloginpin = $externalloginpin['tokenpin'];
  
  //--CALL TO API EXTERNALLOGIN ---
  
  //variable for get externalAddTransaction
  $value = "8.58";
  $clientId = "";
  $label = "teste plugin";
  $mode = "SEL";
  $tips = "";
  $tousr = "testeyvan@adduniti.online";
  $key_code = 'UnitiET$'.$secretkey.$tokenpin.$tokenloginpin.$mode;
  $sha256password_externallogin = hash("sha256", $key_code);
  
  $data_externalogin = array("token" => "$tokenpin", "tokenpin" => "$tokenloginpin", "value" => "$value", "clientId" => "$clientId", "label" => "$label", "mode" => "$mode", "tips" => "$tips", "tousr" => "$tousr", "key" => "$sha256password_externallogin");
  $data_resultexternallogin = json_encode($data_externalogin);
  
  $curl = curl_init();
  curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://www.adduniti.online:9303/UNITI_API_PAY_1.DLL/externalAddTransaction',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_SSL_VERIFYPEER => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS => $data_resultexternallogin,
    CURLOPT_HTTPHEADER => array(
      'Content-Type: application/json'
    ),
  ));
  
  $response = curl_exec($curl);
  curl_close($curl);
  $resultexternalloginpin = json_decode($response, true);
  $qrcodevalue = $resultexternalloginpin['qrcodeb'];
  
  echo QRcode::png($qrcodevalue);
  
}

teste();

