<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/vendor/autoload.php';
$methodId = 55;
$method = $conn->prepare("SELECT * FROM paymentmethods WHERE methodId = ?");
$method->execute([$methodId]);
$methodExtras= json_decode($method->fetch()['methodExtras'], true);

MercadoPago\SDK::setAccessToken($methodExtras['access_token']);

// Handle IPN notification


// Handle return URLs
if (isset($_GET['success'], $_GET['preference_id'])) {
    $preference = MercadoPago\Preference::find_by_id($_GET['preference_id']);
    $transaction_uuid = $preference->external_reference;
    
 switch($_GET['success']) {
    case '1':
        $status = 'approved';
        break;
    case '2':
        $status = 'pending';
        break;
    default:
        $status = 'rejected';
}

    
    if ($status === 'approved') {
        $paymentDetails = $conn->prepare("SELECT * FROM payments WHERE payment_status = 1 AND t_id = ?");
        $paymentDetails->execute([$transaction_uuid]);
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
                
                'order_id' => $transaction_uuid
            ]);
            
            // Add funds to user balance
           
            $conn->prepare("UPDATE clients SET balance = balance + ? WHERE client_id = ?")
                 ->execute([$paidAmount, $user['client_id']]);
        }
        header("Location: " . site_url("addfunds/success"));
    } else {
        header("Location: " . site_url("addfunds/failed"));
    }
    exit();
}

header("Location: " . site_url("addfunds/failed"));
exit;