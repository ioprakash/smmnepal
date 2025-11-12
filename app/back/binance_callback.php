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

$response = file_get_contents('php://input');
$logFile = "binancePayWebhookCallbackFile.json";
  $log = fopen($logFile, "a");
  fwrite($log, $response);
  fclose($log);
$response = json_decode($response);
// var_dump($response);
// exit;
if(isset($response->data)){
    if($response->bizStatus == "PAY_SUCCESS"){
    $data = json_decode($response->data);
    $trx_id = $data->merchantTradeNo;
    $amount = $data->totalFee;
    $payment = $conn->prepare('Select * from payments where payment_extra =:trx_id && payment_status =:status');
    $payment->execute(['trx_id' => $trx_id, 'status' => 1]);
    $payment = $payment->fetch(PDO::FETCH_ASSOC);
    // var_dump($payment);
    // exit;
    if($payment){
        $user = $conn->prepare('Select * from clients where client_id=:client_id');
        $user->execute(array('client_id'=>$payment['client_id']));
        $user = $user->fetch(PDO::FETCH_ASSOC);
        // var_dump($user);
        // exit;
        $update = $conn->prepare('UPDATE payments SET 
        client_balance=:balance, 
        
        payment_status=:status, 
        payment_delivery=:delivery WHERE payment_id=:id');
        $update->execute(
            [
                'balance' => $user['balance'],
                
                'status' => 3,
                'delivery' => 2,
                'id' => $payment['payment_id']
            ]
        );
        $updateBalance = $conn->prepare("UPDATE clients SET balance=:balance WHERE client_id=:id");
    $update =    $updateBalance->execute([
            "balance" => $user["balance"] + $payment['payment_amount'],
            "id" => $user["client_id"]
        ]);
        
    }
 
}
}