<?php


function get_transaction_by_trx_id($trx_id)
{
    global $conn;
    $payment = $conn->prepare('Select * from payments where payment_extra =:trx_id && payment_status =:status');
    $payment->execute(['trx_id' => $trx_id, 'status' => 1]);
    $payment = $payment->fetch(PDO::FETCH_ASSOC);
    return $payment;
}

function verify_webhook()
{



    global $conn;
    $paymentMethod = $conn->prepare("SELECT * FROM paymentmethods WHERE methodId=:id ORDER BY methodPosition ASC");
    $paymentMethod->execute(['id' => 29]);
    $paymentMethod = $paymentMethod->fetch(PDO::FETCH_ASSOC);

    $methodExtras = json_decode($paymentMethod["methodExtras"], 1);
    $apiKey = $methodExtras['api_key'];


    $data = file_get_contents('php://input');

    $logFile = "heleketWebhooklogs.json";
    $log = file_put_contents($logFile,$data);
   
    $dataArray = json_decode($data, true);
   





    if (!$dataArray || !isset($dataArray['sign'])) {
        http_response_code(400);
        exit("Invalid Webhook Data");
    }

    $receivedSign = $dataArray['sign'];
    unset($dataArray['sign']);

    // Generate sign for verification 
    $hash = md5(base64_encode(json_encode($dataArray)) . $apiKey);



    if (!hash_equals($hash, $receivedSign)) {
        http_response_code(403);
        exit("Invalid Signature");
    }

    // Check payment status
    if ($dataArray['status'] === "paid") {

        $trx_id =  $dataArray['order_id'];


        $trx_details = get_transaction_by_trx_id($trx_id);
        if ($trx_details) {
            return true;
        }
        return false;
    } else {
        return false;
    }
}

$check = verify_webhook();

$response = file_get_contents('php://input');

if ($check) {
    if ($check == true) {
        $data = json_decode($response);
        $trx_id = $data->order_id;

        $payment = $conn->prepare('Select * from payments where payment_extra =:trx_id && payment_status =:status');
        $payment->execute(['trx_id' => $trx_id, 'status' => 1]);
        $payment = $payment->fetch(PDO::FETCH_ASSOC);
        // var_dump($payment);
        // exit;
        if ($payment) {
            $user = $conn->prepare('Select * from clients where client_id=:client_id');
            $user->execute(array('client_id' => $payment['client_id']));
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
            echo 'Payment successful';
            exit;
        } else {
            echo 'Transaction ID not found';
            exit;
        }
    } else {
        echo 'Transaction ID not found';
        exit;
    }
} else {
    echo 'Transaction ID not found';
    exit;
}
