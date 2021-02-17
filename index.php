<?php
include('../qrcode/phpqrcode/qrlib.php');

/*
testeyvan2@adduniti.online
Yvan2021

testeyvan@adduniti.online
Yvan2020
*/
//date pour les images stocker
$mydate = getdate(date("U"));
$tempDir = "PayementsClients/$mydate[year]$mydate[hours]$mydate[minutes]$mydate[seconds]";

//variable for get tokenlogin
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
//echo " token = . $tokenpin";

//--CALL TO API EXTERNALLOGIN ---

//variable for get externalloginpin
$mpd = '785632';
$pinsha = sha1($mpd);
$secretkey = "AZERTY";
$key = 'UnitiC$' . $pinsha . $tokenpin . $secretkey;
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
//echo " tokenpin = .$tokenpin";

//--CALL TO API EXTERNALLOGIN ---

//variable for get externalAddTransaction
$value = "1";
$clientId = "";
$label = "teste plugin";
$mode = "SEL";
$tips = "";
$tousr = "";
if (isset($_POST['submit'])) {
  if ($_POST["email"] !== "") {
    $resulmail = addslashes(trim(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)));
    $tousr = $resulmail;
  }
}

$key_code = 'UnitiET$' . $secretkey . $tokenpin . $tokenloginpin . $mode;
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
  CURLOPT_HTTPHEADER => array('Content-Type: application/json'),
));

$response = curl_exec($curl);
curl_close($curl);
$resultexternalloginpin = json_decode($response, true);
$qrtrnid = $resultexternalloginpin["trnid"];
$qrcodevalue = $resultexternalloginpin['qrcodeb'];
//$jsCanvasCode = QRcode::png($qrcodevalue);
$jsCanvasCode = QRcode::png($qrcodevalue, $tempDir . 'adduniti.png', QR_ECLEVEL_M, 4);

/*
  Function pour Annuler la transaction

  $key_code_annuler =  'UnitiAT$'.$qrtrnid.$tokenpin.$secretkey.$tokenloginpin;
  $sha256annuler = hash("sha256", $key_code_annuler);
  $dataAnnuler = array("token" => "$tokenpin", "tokenpin" => "$tokenloginpin", "trnid" => "$qrtrnid" , "key" => "$sha256annuler");
  $data_Annuler = json_encode($dataAnnuler);

  if(isset($_POST['Annuler'])){
    $curl = curl_init();
    curl_setopt_array($curl, array(
      CURLOPT_URL => 'https://www.adduniti.online:9303/UNITI_API_PAY_1.DLL/externalaborttransaction',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_SSL_VERIFYPEER => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS => $data_Annuler,
      CURLOPT_HTTPHEADER => array('Content-Type: application/json'),
    ));
    $response = curl_exec($curl);
    curl_close($curl);
    echo $response;
  }
*/


//Retourne l’état d’une demande de paiement
$key_code_check_validate = 'UnitiCT$' . $qrtrnid . $tokenpin . $secretkey . $tokenloginpin;
$sha256checkvalidate = hash("sha256", $key_code_check_validate);
$dataCheckValidate = array("token" => "$tokenpin", "tokenpin" => "$tokenloginpin", "trnid" => "$qrtrnid", "waitforstatusid" => "", "maxwait" => 120, "key" => "$sha256checkvalidate");
$data_Check_Validate = json_encode($dataCheckValidate);

$curl = curl_init();
curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://www.adduniti.online:9303/UNITI_API_PAY_1.DLL/externalchecktransaction',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_SSL_VERIFYPEER => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
  CURLOPT_POSTFIELDS => $data_Check_Validate,
  CURLOPT_HTTPHEADER => array('Content-Type: application/json'),
));
$response = curl_exec($curl);
curl_close($curl);
echo $response;
?>

<html>
<body>
  <div>
    <?php //testeyvan2@adduniti.online
    echo '<img src="' . $tempDir . 'adduniti.png" />'; ?>
  </div>

  <form method="post">
    <label> Email </label>
    <input type="email" name="email" required><br>
    <input type="submit" name="submit">
  </form>

  <form method="post">
    <input type="submit" name="Annuler">
  </form>
  
</body>
</html>