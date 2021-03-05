<?php
//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require 'dist/email/vendor/autoload.php';

//Instantiation and passing `true` enables exceptions

function sendEmail($toEmail, $toName, $subject, $content) {
    $mail = new PHPMailer(true);
    $mail->CharSet = "UTF-8";

    try {
        //Server settings
        $mail->SMTPDebug = 4;                      //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = 'tesymail0@gmail.com';                     //SMTP username
        $mail->Password   = 'traiDat4';                               //SMTP password
        $mail->SMTPSecure = 'ssl';         //Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
        $mail->Port       = 465;                                    //TCP port to connect to, use 465 for 

        //Recipients
        $mail->setFrom('tesymail0@gmail.com', 'Mailer');
        $mail->addAddress($toEmail, $toName);     //Add a recipient

        $mail->addReplyTo('tesymail0@gmail.com', 'Information');
        $mail->addCC('tesymail0@gmail.com');
        $mail->addBCC('tesymail0@gmail.com');



        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = $subject;
        $mail->Body    = $content;
        $mail->AltBody = $content;

        $mail->send();
        echo 'Gửi mail thành công';
    } catch (Exception $e) {
        echo "Không thể gửi mail. Lỗi: {$mail->ErrorInfo}";
    }
}

sendEmail('chaungoclong2411@gmail.com', 'chau ngoc long', 'xác nhận đơn hàng', 'đơn hàng của bạn đã được duyệt');