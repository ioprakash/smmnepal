<?php
if (!defined('BASEPATH')) {
    die('Direct access to the script is not allowed');
}
define("ADDFUNDS", TRUE);
$title .= " Add Funds";

if ($_SESSION["msmbilisim_userlogin"] != 1 || $user["client_type"] == 1) {
    header("Location:" . site_url('logout'));
}

if ($settings["email_confirmation"] == 1 && $user["email_type"] == 1) {
    header("Location:" . site_url('confirm_email'));
}

if(isset($_POST)){
     $payment_method = $conn->prepare("Select * from paymentmethods where methodId=:id");
     $payment_method->execute(array('id'=>$_POST['payment_method']));
     $payment_method = $payment_method->fetch(PDO::FETCH_ASSOC);
     $payment_method = json_decode($payment_method['methodExtras']);
     
    
    // Generate nonce string
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $nonce = '';
    for($i=1; $i <= 32; $i++)
    {
        $pos = mt_rand(0, strlen($chars) - 1);
        $char = $chars[$pos];
        $nonce .= $char;
    }
    $ch = curl_init();
    $timestamp = round(microtime(true) * 1000);
    // Request body
     $request = array(
       "env" => array(
             "terminalType" => "APP" 
          ), 
       "merchantTradeNo" => $_POST['trx_id'], 
       "orderAmount" => $_POST['payment_amount'], 
       "currency" => "USDT", 
       "goods" => array(
                "goodsType" => "01", 
                "goodsCategory" => "D000", 
                "referenceGoodsId" => $_POST['trx_id'], 
                "goodsName" => "Topup for SMM ", 
                "goodsDetail" => "Payment for balance recharge" 
             ) ,
             'returnUrl' =>site_url('addfunds'),
    ); 
 
    $json_request = json_encode($request);
    $payload = $timestamp."\n".$nonce."\n".$json_request."\n";
    $binance_pay_key = $payment_method->api_key;
    $binance_pay_secret = $payment_method->secret_key;
    $signature = strtoupper(hash_hmac('SHA512',$payload,$binance_pay_secret));
    $headers = array();
    $headers[] = "Content-Type: application/json";
    $headers[] = "BinancePay-Timestamp: $timestamp";
    $headers[] = "BinancePay-Nonce: $nonce";
    $headers[] = "BinancePay-Certificate-SN: $binance_pay_key";
    $headers[] = "BinancePay-Signature: $signature";

    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_URL, "https://bpay.binanceapi.com/binancepay/openapi/v2/order");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json_request);

    $result = curl_exec($ch);
    if (curl_errno($ch)) { echo 'Error:' . curl_error($ch); }
    curl_close ($ch);

    $result = json_decode($result,true);
    if($result['data']['checkoutUrl']){
        
        header("Location:" . $result['data']['checkoutUrl']);
    }else{
      var_dump($result);
      exit;
    }
}else{
   var_dump($result);
   exit;
}