<?php

include('mail.php');
if (!$_POST)
    exit;

// Email address verification, do not edit.
function isEmail($email)
{
    return (preg_match("/^[-_.[:alnum:]]+@((([[:alnum:]]|[[:alnum:]][[:alnum:]-]*[[:alnum:]])\.)+(ad|ae|aero|af|ag|ai|al|am|an|ao|aq|ar|arpa|as|at|au|aw|az|ba|bb|bd|be|bf|bg|bh|bi|biz|bj|bm|bn|bo|br|bs|bt|bv|bw|by|bz|ca|cc|cd|cf|cg|ch|ci|ck|cl|cm|cn|co|com|coop|cr|cs|cu|cv|cx|cy|cz|de|dj|dk|dm|do|dz|ec|edu|ee|eg|eh|er|es|et|eu|fi|fj|fk|fm|fo|fr|ga|gb|gd|ge|gf|gh|gi|gl|gm|gn|gov|gp|gq|gr|gs|gt|gu|gw|gy|hk|hm|hn|hr|ht|hu|id|ie|il|in|info|int|io|iq|ir|is|it|jm|jo|jp|ke|kg|kh|ki|km|kn|kp|kr|kw|ky|kz|la|lb|lc|li|lk|lr|ls|lt|lu|lv|ly|ma|mc|md|me|mg|mh|mil|mk|ml|mm|mn|mo|mp|mq|mr|ms|mt|mu|museum|mv|mw|mx|my|mz|na|name|nc|ne|net|nf|ng|ni|nl|no|np|nr|nt|nu|nz|om|org|pa|pe|pf|pg|ph|pk|pl|pm|pn|pr|pro|ps|pt|pw|py|qa|re|ro|ru|rw|sa|sb|sc|sd|se|sg|sh|si|sj|sk|sl|sm|sn|so|sr|st|su|sv|sy|sz|tc|td|tf|tg|th|tj|tk|tm|tn|to|tp|tr|tt|tv|tw|tz|ua|ug|uk|um|us|uy|uz|va|vc|ve|vg|vi|vn|vu|wf|ws|ye|yt|yu|za|zm|zw)$|(([0-9][0-9]?|[0-1][0-9][0-9]|[2][0-4][0-9]|[2][5][0-5])\.){3}([0-9][0-9]?|[0-1][0-9][0-9]|[2][0-4][0-9]|[2][5][0-5]))$/i", $email));
}

if (!defined("PHP_EOL"))
    define("PHP_EOL", "\r\n");

$fname     = $_POST['fname'];
$lname     = $_POST['lname'];
$email    = $_POST['email'];
$phone    = $_POST['phone'];
$comments = $_POST['comments'];
$date     = $_POST['date'];
$time     = $_POST['time'];

$appointment_var = array();

$appointment_var['afname']     = $fname;
$appointment_var['alname']     = $lname;
$appointment_var['aemail']    = $email;
$appointment_var['aphone']    = $phone;
$appointment_var['acomments'] = $comments;
$appointment_var['adate']     = $date;
$appointment_var['atime']     = $time;



if (trim($fname) == '') {
    echo '<div class="error_message">Please enter your first name.</div>';
    exit();
} else if (trim($lname) == '') {
    echo '<div class="error_message">Please enter your last name.</div>';
    exit();
}
else if (trim($email) == '') {
    echo '<div class="error_message">Please enter a valid email address.</div>';
    exit();
}
else if (trim($phone) == '') {
    echo '<div class="error_message">Please enter a valid phone number.</div>';
    exit();
} else if (trim($date) == '') {
    echo '<div class="error_message">You have chosesn an invalid date.</div>';
    exit();
} else if (trim($time) == '') {
    echo '<div class="error_message">Please pick a time</div>';
    exit();
}

else if (!isEmail($email)) {
    echo '<div class="error_message">You have entered an invalid e-mail address, try again.</div>';
    exit();
}

if (trim($comments) == '') {
    echo '<div class="error_message">Please enter your message.</div>';
    exit();
}

if (get_magic_quotes_gpc()) {
    $comments = stripslashes($comments);
}


// Configuration option.
// Enter the email address that you want to emails to be sent to.
// Example $address = "yourname@yourdomain.com";

$address = "support@example.com";


// Configuration option.
// i.e. The standard subject will appear as, "You've been contacted by John Doe."

// Example, $e_subject = '$name . ' has contacted you via Your Website.';

$e_subject = "You\'ve been contacted by $fname $lname.";


// Configuration option.
// You can change this if you feel that you need to.
// Developers, you may wish to add more fields to the form, in which case you must be sure to add them here.


/* Admin Copy */
$e_body    = "You have an appointment request by $fname $lname from your website, details are as follows." . PHP_EOL . PHP_EOL;
$e_content = "\"$comments\"" . PHP_EOL . PHP_EOL;
$e_reply   = "You can contact $fname $lname by email, $email or by phone $phone";



if (($e_content = file_get_contents("email/appointment-admin.php")) === false) {
    $e_content = "";
}


foreach ($appointment_var as $key => $value) {
    // echo '_var_'.$key.'_var_';
    $e_content = str_replace('_var_' . $key . '_var_', $value, $e_content);
}

/* User Copy */

$f_body    = "We recieved your aappointment request, details are as follows." . PHP_EOL . PHP_EOL;
$f_content = "\"$comments\"" . PHP_EOL . PHP_EOL;
$f_reply   = "You will be soon contacted by one of our representatives";



if (($f_content = file_get_contents("email/appointment-user.html")) === false) {
    $f_content = "";
}
foreach ($appointment_var as $key => $value) {
    $f_content = str_replace('_var_' . $key . '_var_', $value, $f_content);
}


/* Compose Message */
$e_msg = wordwrap($e_body . $e_content . $e_reply, 70);
$f_msg = wordwrap($f_body . $f_content . $f_reply, 70);

$headers = "From: snfdental@gmail.com" . PHP_EOL;
$headers .= "Reply-To: $email" . PHP_EOL;
$headers .= "MIME-Version: 1.0" . PHP_EOL;
$headers .= "Content-type: text/html; charset=utf-8" . PHP_EOL;
$headers .= "Content-Transfer-Encoding: quoted-printable" . PHP_EOL;

/*$f = smtpmailer($email, "do_not_reply@smilenowfamilydental.com", "Smile Now Family Dental", "Appointment Request", $f_msg);
$e = smtpmailer("do_not_reply@smilenowfamilydental.com", "do_not_reply@smilenowfamilydental.com", "Smile Now Family Dental", "Appointment Request", $e_msg);*/

$f = smtpmailer($email, "smilenowfamilydental@gmail.com", "Smile Now Family Dental", "Appointment Request", $f_msg);
$e = smtpmailer("smilenowfamilydental@gmail.com", "smilenowfamilydental@gmail.com", "Smile Now Family Dental", "Appointment Request", $e_msg);


if ($e == 1 and $f == 1) {

    echo "<fieldset>";
    echo "<div id='success_page'>";
    echo "<h2>Appointment request has been created successfully.</h2>";
    echo "<p>Thank you <strong>$fname $lname</strong> for choosing Smile Now Family Dental for your dental needs. Our scheduling coordinator will contact you to confirm your appointment.</p>";
    echo "</div>";
    echo "</fieldset>";

} else {

    echo 'ERROR!';

}

?>
