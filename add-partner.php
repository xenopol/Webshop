<?php
include("mainHeader.php");

$partnerFullName = $_POST["partnerFullName"];
$partnerCompanyName = $_POST["partnerCompanyName"];
$partnerEmail = $_POST["partnerEmail"];
$partnerPassword = $_POST["partnerPassword"];


$loginCode = uniqid("activate");

$sql = "INSERT INTO partners (partner_id, partner_name, partner_company_name, partner_email, partner_password,partner_key) VALUES (NULL, '$partnerFullName', '$partnerCompanyName', '$partnerEmail', '$partnerPassword','$loginCode')";
$result = $conn->query($sql);

$activateURL = "http://theroot.dk/activate-partner.php?loginCode=$loginCode";
$sMessage = "Hi, partner you have signed-up.Your email is $partnerEmail and the profit is $partnerCommission %. To activate your account click on this link $activateURL";
$sSendMessage= urlencode($sMessage);
file_get_contents("http://www.iqvsiq.com/webshop2014/send-email.php?emailTo=$partnerEmail&emailSubject=Account%20activation&emailMessage=$sSendMessage");

//SEND SMS
$message = urlencode("Ba hai sa ne apucam de proiect, ca nu mai avem timp");
$YOUR_PHONE_NUMBER = "91940834";
$YOUR_KEY = "98J6-3Byx-a4Ub-ltqk";
//$sResponse = file_get_contents("http://iqvsiq.com/tekstea_v1/php-server/send-sms.php?do={%22secretKey%22:%22".$YOUR_KEY."%22,%22mobileNumber%22:%22".$YOUR_PHONE_NUMBER."%22,%22message%22:%22Paul%20Macinic%20".$message."%22}");

echo '<div class="container"><div class="alert alert-success" role="alert">Your account has been created. An email has been sent to your email address in order to activate your account.</div></div>';
?>


</script>