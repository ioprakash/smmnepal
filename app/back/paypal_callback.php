<?php

$methodId = 54;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verify PayPal IPN (Instant Payment Notification)
    $raw_post_data = file_get_contents('php://input');
    $raw_post_array = explode('&', $raw_post_data);
    $myPost = [];
    
    foreach ($raw_post_array as $keyval) {
        $keyval = explode('=', $keyval);
        if (count($keyval) == 2) {
            $myPost[$keyval[0]] = urldecode($keyval[1]);
        }
    }
    
    // Build verification request
    $req = 'cmd=_notify-validate';
    foreach ($myPost as $key => $value) {
        $value = urlencode($value);
        $req .= "&$key=$value";
    }
    
    // Post back to PayPal for validation
    $paypal_url = ($methodExtras['sandbox'] ?? true) ? 
        'https://ipnpb.sandbox.paypal.com/cgi-bin/webscr' : 
        'https://ipnpb.paypal.com/cgi-bin/webscr';
    
    $ch = curl_init($paypal_url);
    curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
    curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Connection: Close',
        'User-Agent: Your-IPN-User-Agent'
    ]);
    
    $res = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    // Process verification
    if ($http_code == 200 && strcmp($res, "VERIFIED") == 0) {
        // Payment data from PayPal
        $orderId = $_POST['custom'] ?? '';
        $paymentStatus = $_POST['payment_status'] ?? '';
        $paymentAmount = $_POST['mc_gross'] ?? 0;
        $paymentCurrency = $_POST['mc_currency'] ?? '';
        $txnId = $_POST['txn_id'] ?? '';
        
        // Get payment details from database
        $paymentDetails = $conn->prepare("SELECT * FROM payments WHERE payment_extra=:orderId");
        $paymentDetails->execute(["orderId" => $orderId]);
        
        if ($paymentDetails->rowCount()) {
            $paymentDetails = $paymentDetails->fetch(PDO::FETCH_ASSOC);
            
            // Check if payment already processed
            if (!countRow([
                'table' => 'payments',
                'where' => [
                    'client_id' => $user['client_id'],
                    'payment_method' => $methodId,
                    'payment_status' => 3,
                    'payment_delivery' => 2,
                    'payment_extra' => $orderId
                ]
            ])) {
                // Verify payment details
                if (strtolower($paymentStatus) == 'completed' && 
                    $paymentDetails["payment_amount"] == $paymentAmount && 
                    strtoupper($paymentCurrency) == strtoupper($methodCurrency)) {
                    
                    $paidAmount = floatval($paymentDetails["payment_amount"]);
                    
                    // Apply fees if any
                    if ($paymentFee > 0) {
                        $fee = ($paidAmount * ($paymentFee / 100));
                        $paidAmount -= $fee;
                    }
                    
                    // Apply bonus if applicable
                    if ($paymentBonusStartAmount != 0 && $paidAmount > $paymentBonusStartAmount) {
                        $bonus = $paidAmount * ($paymentBonus / 100);
                        $paidAmount += $bonus;
                    }
                    
                    // Convert currency if needed
                    $paidAmount = from_to($currencies_array, $methodCurrency, $settings["site_base_currency"], $paidAmount);
                    
                    // Update payment status
                    $update = $conn->prepare('UPDATE payments SET 
                        client_balance=:balance,
                        payment_status=:status, 
                        payment_delivery=:delivery 
                        WHERE payment_id=:id');
                    $update->execute([
                        'balance' => $user["balance"],
                        'status' => 3,
                        'delivery' => 2,
                        'id' => $paymentDetails['payment_id']
                    ]);
                    
                    // Update user balance
                    $balance = $conn->prepare('UPDATE clients SET balance=:balance WHERE client_id=:id');
                    $balance->execute([
                        "balance" => $user["balance"] + $paidAmount,
                        "id" => $user["client_id"]
                    ]);
                    
                    // Log successful payment
                    error_log("PayPal Payment Verified: $txnId");
                } else {
                    error_log("PayPal Payment Verification Failed: Amount/Currency mismatch");
                    errorExit("Payment verification failed.");
                }
            } else {
                error_log("PayPal Payment Already Processed: $orderId");
                errorExit("Order ID is already used.");
            }
        } else {
            error_log("PayPal Order ID Not Found: $orderId");
            errorExit("Order ID not found.");
        }
    } elseif (strcmp($res, "INVALID") == 0) {
        error_log("PayPal IPN Verification Failed: $raw_post_data");
        errorExit("Invalid payment data.");
    }
} else {
    
    
  
    
    $token = $_GET['token'] ?? null;
    $payerId = $_GET['PayerID'] ?? null;
    
    if (!$token || !$payerId) {
        header("Location: " . site_url("addfunds/failed"));
        exit();
    }
    
  
    $methodId = 54; // PayPal method ID
    $method = $conn->query("SELECT * FROM paymentmethods WHERE methodId = {$methodId}")->fetch();
    $methodExtras = json_decode($method['methodExtras'], true);
  
    $paypal_client_id = $methodExtras['client_id'];
    $paypal_secret = $methodExtras['secret'];
    
    $sandbox = ($methodExtras['sandbox'] !== 'live') ? true : false;
    

 
    $auth_url = $sandbox ? 
        'https://api.sandbox.paypal.com/v1/oauth2/token' : 
        'https://api.paypal.com/v1/oauth2/token';
 
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $auth_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, 'grant_type=client_credentials');
    curl_setopt($ch, CURLOPT_USERPWD, $paypal_client_id . ':' . $paypal_secret);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Accept: application/json',
        'Accept-Language: en_US',
        'Content-Type: application/x-www-form-urlencoded'
    ]);
    
    $response = curl_exec($ch);
   
    $access_token = json_decode($response)->access_token;
    curl_close($ch);
    
    // 4. Capture the payment
    $capture_url = ($sandbox ? 
        'https://api.sandbox.paypal.com' : 'https://api.paypal.com') . 
        "/v2/checkout/orders/{$token}/capture";
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $capture_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $access_token
    ]);
    
    $response = curl_exec($ch);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $result = json_decode($response);
    curl_close($ch);

    
    if ($httpcode == 201 && $result->status == 'COMPLETED') {
        $txn_id = $result->purchase_units[0]->payments->captures[0]->id;
        $order_id = $result->purchase_units[0]->reference_id;
        

      

        $paymentDetails = $conn->prepare("SELECT * FROM payments WHERE payment_status = 1 AND payment_extra = ?");
        $paymentDetails->execute([$order_id]);
        $payment = $paymentDetails->fetch(PDO::FETCH_ASSOC);
        if($payment){
            $user = $conn->prepare("SELECT * FROM clients WHERE client_id = ?");
            $user->execute([$payment['client_id']]);
            $user = $user->fetch(PDO::FETCH_ASSOC);
            $paidAmount = $payment['payment_amount'];
        
        
            // Update database
            $update = $conn->prepare("UPDATE payments SET 
                payment_status = 3, 
                payment_delivery = 2
                WHERE t_id = :order_id");
            
            $update->execute([
                
                'order_id' => $order_id
            ]);
            
            // Add funds to user balance
           
            $conn->prepare("UPDATE clients SET balance = balance + ? WHERE client_id = ?")
                 ->execute([$paidAmount, $user['client_id']]);
            
            header("Location: " . site_url("addfunds/success"));
            
        }else{
            header("Location: " . site_url("addfunds/failed"));
            exit;
        }
        


      
    } else {
        error_log("PayPal Capture Failed: " . $response);
        header("Location: " . site_url("addfunds/failed"));
    }
    exit();

}