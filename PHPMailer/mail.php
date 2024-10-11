<?php 
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function sendMail($email,$v_code)
{

    require 'PHPMailer/PHPMailer.php';
    require 'PHPMailer/SMTP.php"';
    require 'PHPMailer/Exception.php'; 
    $mail = new PHPMailer(true);

    try {

        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = 'wyslijdonejta@gmail.com';                     //SMTP username
        $mail->Password   = 'bksp usez urmw yxyi';                               //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
        $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
    
        //Recipients
        $mail->setFrom('wyslijdonejta@gmail.com', 'Verification');
        $mail->addAddress($email);    

        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = 'Email verification';
        $mail->Body    = "<a href='http://localhost/projekt/PHPMailer/verify.php?email=$email&v_code=$v_code'>Verify</a>";
    
        $mail->send();
        return true;
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
?>