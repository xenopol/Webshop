<?php
function displayProducts($status, $orderBy){
    global $conn;
    $sql = "SELECT * FROM products WHERE product_active = '$status'  ORDER BY $orderBy ";
    $result = $conn->query($sql);
    displayProductsCode($conn, $result);
}
function displayMyProducts(){
    global $conn;
    $sql = "SELECT * FROM products WHERE partner_id IS NULL AND product_active= '1'";
    $result = $conn->query($sql);
    displayProductsCode($conn, $result);
}
function displaySearchedProducts($searchInput){
    global $conn;

    $sql = "SELECT * FROM products WHERE product_name LIKE '%$searchInput%' AND product_active = '1'";
    $result = $conn->query($sql);

    displayProductsCode($conn, $result);
}

function displayProductsCode($conn, $result){
    if($conn->affected_rows>0){
        while($row = $result->fetch_assoc()) {
            ?>
            <div class="col-sm-6 col-md-4 product" data-productId="<?php echo $row["product_id"]; ?>">
                <div class="thumbnail" style="height: 500px">
                    <img class="img-responsive img-rounded" src="<?php echo $row["product_image"]; ?>"style="height: 300px">
                    <?php
                    if($row["product_quantity"]==0){
                        ?>
                        <span class="label label-danger pull-right outOfStock">Out of stock</span>
                    <?php
                    }
                    ?>
                    <div class="caption">
                        <h3><?php echo $row["product_name"]; ?></h3>
                        <strong>Price: <?php echo $row["product_price"]; ?> DKK</strong>
                        <p><?php echo $row["product_description"]; ?></p>
                        <?php
                        if(!$_SESSION["partner"]){
                        ?>
                        <p class="pull-left"><a class="btn btn-primary buyProduct" role="button">Buy</a>
                            <?php
                            }
                            $url = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
                            if (false !== strpos($url,'admin-backend')) {
                            ?>
                        <p class="pull-left"><a class="btn btn-primary editProduct" role="button">Edit</a>
                            <a class="btn btn-default deleteProduct" role="button">Delete</a></p>
                    <?php
                    }
                    ?>
                    </div>
                </div>
            </div>
        <?php
        }
    }else{
        echo "<h1>Error</h1>";
    }
}

function generateJsonFile(){
    global $conn;
    $oProducts = json_decode('{"products":[]}');

    $sql = "SELECT * FROM products WHERE partner_id IS NULL AND product_active = '1'";
    $result = $conn->query($sql);

    while($row = mysqli_fetch_array($result)){
        $sProduct = '{"id":"'.$row['product_id'].'","name":"'.$row['product_name'].'","price":"'.$row['product_price'].'","image":"'.$row['product_image'].'"}';
        $oProduct = json_decode($sProduct);
        array_push($oProducts->products, $oProduct);
    }

    $sProducts = json_encode($oProducts, JSON_UNESCAPED_SLASHES);

    file_put_contents("webshop2014/products.json", $sProducts);
}
function seeAllTable($table, $user){
    global $conn;
    $sql = "SELECT * FROM $table";
    $result = $conn->query($sql);
    ?>
    <div class="container">
    <table class="table table-striped table-responsive">
        <tr>
            <th>Full Name</th>
            <th>Email</th>
    <?php
    if($table =="partners"){
    ?>
            <th>Company Name</th>
            <th>Commission</th>
        </tr>

        <?php
        }
    while($row = $result->fetch_assoc()){
    ?>
        <tr>
            <td><?php echo $row[$user."_name"] ?></td>
            <td><?php echo $row[$user."_email"] ?></td>
            <?php
            if($table =="partners"){
                ?>
                <td><?php echo $row[$user."_company_name"] ?></td>
                <td><?php echo $row[$user."_commission"] ?></td>
            <?php
            }
            ?>
        </tr>
<?php
    }
    ?>
    </table>
    </div>
        <?php
}
function seeAllOrders(){
    global $conn;
    $sql = "SELECT * FROM orders";
    $result = $conn->query($sql);
    ?>
    <div class="container">
        <table class="table table-striped table-responsive">
            <tr>
                <th>Partner's Name</th>
                <th>Product's Name</th>
                <th>Customer's Name</th>
                <th>Customer's Phone Number</th>
                <th>Product's Price</th>
                <th>Order's Delivery Address</th>
                <th>Order's Date</th>
            </tr>
        <?php
            while($row = $result->fetch_assoc()){
                ?>
                <tr>
                    <td><?php echo $row["order_partner_name"]; ?></td>
                    <td><?php echo $row["order_product_name"]; ?></td>
                    <td><?php echo $row["order_customer_name"]; ?></td>
                    <td><?php echo $row["order_phone_number"]; ?></td>
                    <td><?php echo $row["order_product_price"]; ?></td>
                    <td><?php echo $row["order_delivery_address"], $row["order_delivery_city"], $row["order_delivery_postcode"]; ?></td>
                    <td><?php echo $row["order_date"]; ?></td>
                </tr>
                <?php
            }
            ?>
        </table>
    </div>

<?php
}
function totalIncome(){
    global $conn;
    $sql = "SELECT * FROM orders";
    $result = $conn->query($sql);

    $totalIncome = 0;

    while($row = $result->fetch_assoc()){
        $totalIncome += $row["order_product_price"];
    }
    echo $totalIncome;
}
function sendOrderToPartner($productPartnerId,$partnerName,$productId,$productName,$productPrice, $customerName, $customerPhone, $customerAddress, $customerCity, $customerPostcode){
        $file = 'webshop2014/partner-request.json';
        // Open the file to get existing content
        $currentProducts = file_get_contents($file);
        $oCurrentProducts = json_decode($currentProducts);

        $sProduct = '{"partnerId":"'.$productPartnerId.'", "partnerName":"'.$partnerName.'", "productId":"'.$productId.'","productName":"'.$productName.'","price":"'.$productPrice.'","clientName":"'.$customerName.'",
    "clientPhone":"'.$customerPhone.'",
    "clientAddress":"'.$customerAddress.$customerCity.$customerPostcode.'"}';

        $oProduct = json_decode($sProduct);

        array_push($oCurrentProducts->orders, $oProduct);

        $sProducts = json_encode($oCurrentProducts);

        // // Write the contents back to the file
        file_put_contents($file, $sProducts);
}
