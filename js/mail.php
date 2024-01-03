<?php
function smtpmailer($to, $from, $from_name, $subject, $body) {
require_once "vendor/autoload.php";
require_once('PHPMailer-master/PHPMailerAutoload.php');

$mail = new PHPMailer(true);

//Enable SMTP debugging.
$mail->SMTPDebug = 0;
//Set PHPMailer to use SMTP.
$mail->isSMTP();
  //Set SMTP host name
$mail->Host = "mail.smilenowfamilydental.com";
//Set this to true if SMTP host requires authentication to send email
$mail->SMTPAuth = true;
//Provide username and password
$mail->Username = "do_not_reply@smilenowfamilydental.com";
$mail->Password = "Snfd@0101";
//If SMTP requires TLS encryption then set it
$mail->SMTPSecure = "tls";
//Set TCP port to connect to
$mail->Port = 587;

$mail->From = "do_not_reply@smilenowfamilydental.com";
$mail->FromName = "Smile Now Family Dental";

$mail->addAddress($to, "Recepient Name");

$mail->isHTML(true);

$mail->Subject = $subject;
$mail->Body = $body;
$mail->AltBody = "This is the plain text version of the email content";

if(!$mail->send())
{
    return 0;
    echo "Mailer Error: " . $mail->ErrorInfo;
}
else
{
    return 1;
}}
?>
