<?php
session_start();
include ("conn.php");
include ("functions.php");

//Add/Edit Product
$productId = $_GET["productId"];
$productName = $_GET["productName"];
$productPrice = $_GET["productPrice"];
$productDescription = $_GET["productDescription"];
$productImage = $_GET["productImage"];
$productQuantity = $_GET["productQuantity"];
//Search Product

switch($_GET['sFunction']){
    case "addProduct":
        addProduct();
        break;

    case "editProduct":
        editProduct();
        break;

    case "searchProduct":
        searchProduct();
        break;

    case "deleteProduct":
        deleteProduct();
        break;

    case "buyProduct":
        buyProduct();
        break;

    case "orderByProduct":
        orderByProduct();
        break;

    case "orderBySliderProduct":
        orderBySliderProduct();
        break;

    case "refreshProductsTable":
        refreshProductsTable();
        break;
}
function addProduct(){
    global $productName, $productPrice, $productDescription, $productImage, $conn;
    $sql = "INSERT INTO products (product_name, product_description, product_price, product_image) VALUES ('$productName', '$productDescription', '$productPrice', '$productImage')";
    $result = $conn->query($sql);
    generateJsonFile();

    if($conn->affected_rows>0){
        echo '{"response":"success"}';
    }else{
        echo '{"response":"error"}';
    }
}

function editProduct(){
    global $conn, $productId, $productName, $productDescription, $productPrice, $productImage, $productQuantity;

    $sql = "UPDATE products SET product_name = '$productName', product_description = '$productDescription', product_price = '$productPrice', product_image = '$productImage', product_quantity = '$productQuantity' WHERE product_id = '$productId'";
    $result = $conn->query($sql);
    generateJsonFile();

    if ($conn->affected_rows>0) {
        echo '{"response" : "success"}';
    } else {
        echo '{"response" : "error"}';
    }
}

function searchProduct (){
    $searchInput = $_GET["searchInput"];
    displaySearchedProducts($searchInput);
}

function deleteProduct(){
    global $conn, $productId;

    $sql="UPDATE products SET product_active='0' WHERE product_id='$productId'";
    $result = $conn->query($sql);

    generateJsonFile();

    if($conn->affected_rows>0){
        echo '{"response":"success"}';
    }else{
        echo '{"response":"error"}';
    }
}
function buyProduct(){
    global $conn, $productId;
    $customerName = $_GET["customerName"];
    $customerEmail = $_GET["customerEmail"];
    $customerPassword = $_GET["customerPassword"];
    $customerAddress = $_GET["customerAddress"];
    $customerCity = $_GET["customerCity"];
    $customerPostcode = $_GET["customerPostcode"];
    $customerPhone = $_GET["customerPhone"];

    //select partner_id from database
    $sql = "SELECT * FROM products WHERE product_id = '$productId'";
    $result = $conn->query($sql);
    if($conn->affected_rows>0){
        while($row = $result->fetch_assoc()){
            $productPartnerId = $row["partner_id"];
            $productName = $row["product_name"];
            $productPrice = $row["product_price"];
        }
    }

    if($_SESSION["customer"]){ //if customer has an account, take the details from session
        $customerId = $_SESSION["customer"]["id"];
        $customerName = $_SESSION["customer"]["name"];
        $customerEmail = $_SESSION["customer"]["email"];
        $customerAddress = $_SESSION["customer"]["address"];
        $customerCity = $_SESSION["customer"]["city"];
        $customerPostcode = $_SESSION["customer"]["postcode"];
        $customerPhone = $_SESSION["customer"]["phone"];

        if($productPartnerId){ //if product is not local
            //insert order into database
            $sql = "SELECT * FROM partners WHERE partner_id = '$productPartnerId'";
            $result = $conn->query($sql);
            while($row = $result->fetch_assoc()){
                $partnerName = $row["partner_name"];
            }

            $sql = "INSERT INTO orders (order_product_id,order_product_name,order_product_price,order_partner_id,order_partner_name,order_customer_id,order_customer_name,order_delivery_address,order_delivery_city,order_delivery_postcode,order_phone_number)
                    VALUES ('$productId','$productName','$productPrice','$productPartnerId','$partnerName','$customerId','$customerName','$customerAddress','$customerCity','$customerPostcode','$customerPhone')";
            $result = $conn->query($sql);

            sendOrderToPartner($productPartnerId,$partnerName,$productId,$productName,$productPrice, $customerName, $customerPhone, $customerAddress, $customerCity, $customerPostcode);


            if($conn->affected_rows>0){
                echo '{"response":"success"}';
            }else{
                echo '{"response":"error"}';
            }
        }else{// if product is local
            $sql = "INSERT INTO orders (order_product_id,order_product_name,order_product_price,order_partner_id,order_partner_name,order_customer_id,order_customer_name,order_delivery_address,order_delivery_city,order_delivery_postcode,order_phone_number)
                        VALUES ('$productId','$productName','$productPrice',NULL ,'Local' ,'$customerId','$customerName','$customerAddress','$customerCity','$customerPostcode','$customerPhone')";
            $result = $conn->query($sql);

            if($conn->affected_rows>0){
                echo '{"response":"success"}';
            }else{
                echo '{"response":"error"}';
            }
        }

    }else{ //if customer doesn't have an account, take the details from form
        //insert customer into database
        $sql = "INSERT INTO customers (customer_name, customer_email, customer_password, customer_address, customer_city, customer_postcode, customer_phone_number) VALUES ('$customerName', '$customerEmail', '$customerPassword', '$customerAddress', '$customerCity', '$customerPostcode', '$customerPhone')";
        $result = $conn->query($sql);
        if($conn->affected_rows>0){
            //select customer_id from database
            $sql = "SELECT * FROM customers WHERE customer_email = '$customerEmail'";
            $result = $conn->query($sql);
            if($conn->affected_rows>0){
                while($row = $result->fetch_assoc()){
                    $customerId = $row["customer_id"];
                }
            }

            if($productPartnerId){ //if product is not local
                //insert order into database
                $sql = "SELECT * FROM partners WHERE partner_id = $productPartnerId";
                $result = $conn->query($sql);
                while($row = $result->fetch_assoc()){
                    $partnerName = $row["partner_name"];
                }
                $sql = "INSERT INTO orders (order_product_id,order_product_name,order_product_price,order_partner_id,order_partner_name,order_customer_id,order_customer_name,order_delivery_address,order_delivery_city,order_delivery_postcode,order_phone_number)
                    VALUES ('$productId','$productName','$productPrice','$productPartnerId','$partnerName','$customerId','$customerName','$customerAddress','$customerCity','$customerPostcode','$customerPhone')";
                $result = $conn->query($sql);

                sendOrderToPartner($productPartnerId,$partnerName,$productId,$productName,$productPrice, $customerName, $customerPhone, $customerAddress, $customerCity, $customerPostcode);

                if($conn->affected_rows>0){
                    echo '{"response":"success"}';
                }else{
                    echo '{"response":"error"}';
                }
            }else{// if product is local
                $sql = "INSERT INTO orders (order_product_id,order_product_name,order_product_price,order_partner_id,order_partner_name,order_customer_id,order_customer_name,order_delivery_address,order_delivery_city,order_delivery_postcode,order_phone_number)
                        VALUES ('$productId','$productName','$productPrice',NULL ,'Local' ,'$customerId','$customerName','$customerAddress','$customerCity','$customerPostcode','$customerPhone')";
                $result = $conn->query($sql);

                if($conn->affected_rows>0){
                    echo '{"response":"success"}';
                }else{
                    echo '{"response":"error"}';
                }
            }
        }else{
            echo '{"response":"error"}';
        }
    }

    //send email to customer
    $sMessage = "Hi, $customerName  Your order has been dispatched and will be with you shortly!
Please retain this email as your proof of purchase. The product will be sent to this address: $customerAddress, $customerCity, $customerPostcode. You will be contacted at this phone number: $customerPhone.";
    $sSendMessage= urlencode($sMessage);
    file_get_contents("http://www.iqvsiq.com/webshop2014/send-email.php?emailTo=$customerEmail&emailSubject=Order%20info&emailMessage=$sSendMessage");

    //send sms to customer
    $YOUR_PHONE_NUMBER = $customerPhone;
    $sms = urlencode("Dear $customerName. You have ordered: $productName. It will arrive shortly.");
    $YOUR_KEY = "98J6-3Byx-a4Ub-ltqk";
    $sResponse = file_get_contents("http://iqvsiq.com/tekstea_v1/php-server/send-sms.php?do={%22secretKey%22:%22".$YOUR_KEY."%22,%22mobileNumber%22:%22".$YOUR_PHONE_NUMBER."%22,%22message%22:%22".$sms."%22}");

}
function refreshProductsTable(){
    global $conn;
    $oLinks = json_decode('{"links":[]}');

    $sql = "SELECT * FROM partners WHERE partner_active = '1'";
    $result = $conn->query($sql);
    if($conn->affected_rows>0) {
        while ($row = $result->fetch_assoc()) {
            $sUrl = '{"id":"' . $row['partner_id'] . '","url":"' . $row['partner_url'] . '"}';
            // Make the product$row['product_name'] string into a product object, so you can push it
            $oUrl = json_decode($sUrl);
            // Push the object into the array of products
            array_push($oLinks->links, $oUrl);
        }

    }
    $sql = "DELETE FROM products WHERE partner_id IS NOT NULL ";
    $result = $conn->query($sql);

    foreach($oLinks->links as $link){

        $sLink = $link->url;
        $linkId = $link->id;
        $sJson = file_get_contents($sLink);
        $oWebshop = json_decode($sJson);
        $oProducts = $oWebshop->products;

        if(is_array($oProducts)) {
            foreach ($oProducts as $oProduct) {
                $sDescription = addslashes($oProduct->description);
                $sName = addslashes($oProduct->name);
                $sql = "INSERT INTO products (product_name, product_description, product_price, product_image, partner_id) VALUES ('$sName', '$sDescription', '$oProduct->price', '$oProduct->image', '$linkId')";
                $result = $conn->query($sql);
            }
            if ($conn->affected_rows > 0) {
                //echo '{"response": "success"}';
            } else {
                //echo '{"response": "error"}';
            }
        }
    }
}
function orderByProduct(){
    $orderBy = $_GET["orderBy"];
    displayProducts(1, $orderBy);
}
function orderBySliderProduct(){
    global $conn;
    $sliderValue = $_GET["sliderValue"];

    $sql = "SELECT * FROM products WHERE product_active = '1' AND product_price <= '$sliderValue' ORDER BY product_price DESC ";
    $result = $conn->query($sql);

    displayProductsCode($conn, $result);
}



