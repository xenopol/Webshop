<?php
session_start();
include ("conn.php");

$sFullName = $_GET['fullName'];
$sEmail = $_GET['email'];
$sPassword = $_GET['password'];
$sApiUrl = $_GET['apiUrl'];
$sCommission = $_GET['commission'];

switch($_GET['sFunction']){
    /*case "createAccount":
        createAccount();
        break;*/

    case "login":
        login();
        break;

    case "logout":
        logout();
        break;

    case "becomePartner":
        becomePartner();
        break;
}

/*function createAccount(){
    global $conn, $sPassword, $sEmail, $sFullName;

    $loginCode = uniqid("activate");
    $sql = "INSERT INTO partners (partner_id, partner_name, partner_email, partner_password, partner_key) VALUES (NULL, '$sFullName', '$sEmail', '$sPassword', '$loginCode')";
    $result = $conn->query($sql);

    if ($conn->affected_rows>0) {
        $activateURL = "http://theroot.dk/activate-partner.php?loginCode=$loginCode";
        $sMessage = "Hi, partner you have signed-up.Your email is $sEmail. To activate your account click on this link $activateURL";
        $sSendMessage= urlencode($sMessage);
        file_get_contents("http://www.iqvsiq.com/webshop2014/send-email.php?emailTo=$sEmail&emailSubject=Account%20activation&emailMessage=$sSendMessage");

        echo '{"sFunction":"createAccount", "response":"success"}';
    }else{
        echo '{"sFunction":"createAccount", "response":"error"}';
    }

}*/

function login(){
    global $sPassword, $sEmail, $conn;

    $sql = "SELECT * FROM partners WHERE partner_email = '$sEmail' AND partner_password = '$sPassword'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()){
            $_SESSION['partner'] = array(
                'id' => $row["partner_id"],
                'name' => $row["partner_name"],
                'email' => $row["partner_email"],
                'url' => $row["partner_url"],
                'admin' => $row["partner_type"]
            );
        }
        echo '{"sFunction":"login", "response":"partner"}';
    }else {
        $sql = "SELECT * FROM customers WHERE customer_email = '$sEmail' AND customer_password = '$sPassword'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $_SESSION['customer'] = array(
                    'id' => $row["customer_id"],
                    'name' => $row["customer_name"],
                    'email' => $row["customer_email"],
                    'address' => $row["customer_address"],
                    'city' => $row["customer_city"],
                    'postcode' => $row["customer_postcode"],
                    'phone' => $row["customer_phone_number"]
                );
            }
            echo '{"sFunction":"login", "response":"customer"}';
        }else{
            echo '{"sFunction":"login", "response":"error"}';
        }
    }
}

function logout(){
    session_destroy();
}

function becomePartner(){
    global $sApiUrl, $sCommission, $conn;
    $sEmail = $_SESSION["partner"]["email"];
    $partnerId = $_SESSION["partner"]["id"];

    $sql = "UPDATE partners SET partner_url = '$sApiUrl', partner_commission = '$sCommission' WHERE partner_email = '$sEmail'";
    $result = $conn->query($sql);
    if ($conn->affected_rows>0) {
        echo '{"response": "success"}';
    } else {
        echo '{"response": "error"}';
    }

    $_SESSION["partner"]["url"] = $sApiUrl;
    $sJson = file_get_contents($sApiUrl);
    $oWebshop = json_decode($sJson);
    $oProducts = $oWebshop->products;
    //$oCurrency = $oWebshop->currency;

    foreach($oProducts as $oProduct) {
        $sName = addslashes($oProduct->name);
        $sDescription = addslashes($oProduct->description);
        $sql = "INSERT INTO products (product_name, product_description, product_price, product_image, partner_id) VALUES ('$sName', '$sDescription', '$oProduct->price', '$oProduct->image', '$partnerId')";
        $result = $conn->query($sql);
    }


}


