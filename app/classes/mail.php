<?php

if(!defined('BASEPATH')) {
   die('Direct access to the script is not allowed');
}

function sendMail($arr)
{
    global $conn, $settings, $mail;
    try {
        $smtpServer = trim((string)($settings["smtp_server"] ?? ""));
        $smtpUser = trim((string)($settings["smtp_user"] ?? ""));
        $smtpPass = (string)($settings["smtp_pass"] ?? "");
        $smtpPort = (int)($settings["smtp_port"] ?? 587);
        $smtpProtocol = strtolower(trim((string)($settings["smtp_protocol"] ?? "")));

        $mail->SMTPDebug = 0;
        $mail->CharSet = "utf-8";
        $mail->Encoding = "base64";
        $mail->SetLanguage("tr", "phpmailer/language");

        // Avoid leaking recipients between calls
        $mail->clearAllRecipients();
        $mail->clearReplyTos();
        $mail->clearAttachments();

        if ($smtpServer !== "") {
            $mail->isSMTP();
            $mail->Host = $smtpServer;
            $mail->SMTPAuth = ($smtpUser !== "");
            $mail->Username = $smtpUser;
            $mail->Password = $smtpPass;
            $mail->Port = $smtpPort > 0 ? $smtpPort : 587;

            if ($smtpProtocol === "ssl" || $smtpProtocol === "tls") {
                $mail->SMTPSecure = $smtpProtocol;
            } else {
                // Default to opportunistic STARTTLS if supported by server
                $mail->SMTPSecure = "tls";
            }
        } else {
            // Fallback to local mail() when SMTP is not configured
            $mail->isMail();
        }

        $fromEmail = $smtpUser !== "" ? $smtpUser : ((string)($settings["admin_mail"] ?? ""));
        $fromName = (string)($settings["site_title"] ?? "");
        if ($fromEmail !== "") {
            $mail->setFrom($fromEmail, $fromName);
        }

        if (is_array($arr["mail"])) :
            foreach ($arr["mail"] as $goMail) {
                $mail->ClearAddresses();
                $mail->addAddress($goMail);
                $mail->isHTML(true);
                $mail->Subject = $arr["subject"];
                $mail->Body = $arr["body"];
                $mail->send();
            }
        else :
            $mail->addAddress($arr["mail"]);
            $mail->isHTML(true);
            $mail->Subject = $arr["subject"];
            $mail->Body = $arr["body"];
            $mail->send();
        endif;
        return 1;
    } catch (Exception $e) {
        $logDir = __DIR__ . '/../../storage/logs';
        if (!is_dir($logDir)) {
            @mkdir($logDir, 0755, true);
        }
        @file_put_contents(
            $logDir . '/mail.log',
            '[' . date('c') . '] Mail error: ' . $e->getMessage() . "\n",
            FILE_APPEND
        );
        return 0;
    }
}
