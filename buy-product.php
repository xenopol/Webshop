<?php
include ("mainHeader.php");
$productId = $_GET["id"];

$sql = "SELECT * FROM products WHERE product_id='$productId'";
$result = $conn->query($sql);

if($conn->affected_rows>0){
    while($row = $result->fetch_assoc()){
        ?>
        <div class="container">
            <div class="alerts"></div>
            <div class="row">
                <div class="col-sm-6 col-md-4 col-lg-5 product" data-productId="<?php echo $row["product_id"]; ?>">
                    <div class="thumbnail">
                        <img class="img-responsive img-rounded" src="<?php echo $row["product_image"]; ?>"style="height: 300px">
                        <div class="caption" style="overflow: hidden">
                            <h3><?php echo $row["product_name"]; ?></h3>
                            <strong>Price: <?php echo $row["product_price"]; ?> DKK</strong>
                            <p><?php echo $row["product_description"]; ?></p>
                                <?php
                                if($row["product_quantity"]==0){
                                    ?>
                                    <span class="label label-danger pull-right">Out of stock</span>
                                <?php
                                }
                                ?>
                        </div>
                    </div>
                </div>
    <?php

    }
}?>

                <div class="customerForm col-lg-7">
                    <form role="form">
                        <?php
                            if(!$_SESSION["customer"]){
                               ?>
                                <div class="form-group">
                                    <label>Full Name</label>
                                    <input type="text" class="form-control" id="customerFullName" placeholder="Enter full name">
                                </div>
                                <div class="form-group">
                                    <label>Email</label>
                                    <input type="email" class="form-control" id="customerEmail" placeholder="Enter email">
                                </div>
                                <div class="form-group">
                                    <label>Password</label>
                                    <input type="password" class="form-control" id="customerPassword" placeholder="Enter password">
                                </div>
                                <div class="form-group">
                                    <label>Retype Password</label>
                                    <input type="password" class="form-control" id="customerPassword" placeholder="Retype password">
                                </div>
                                <div class="form-group">
                                    <label>Address</label>
                                    <input type="text" class="form-control" id="customerAddress" placeholder="Enter address">
                                </div>
                                <div class="form-group">
                                    <label>City</label>
                                    <input type="text" class="form-control" id="customerCity" placeholder="Enter city">
                                </div>
                                <div class="form-group">
                                    <label>Postcode</label>
                                    <input type="text" class="form-control" id="customerPostcode" placeholder="Enter postcode">
                                </div>
                                <div class="form-group">
                                    <label>Phone Number</label>
                                    <input type="text" class="form-control" id="customerPhone" placeholder="Enter phone number">
                                </div>
                        <?php
                            }
                        ?>
                        <input type="button" class="btn btn-primary buyProductFinal" value="Buy Product">
                    </form>
                </div>
            </div><!-- Close Row -->
        </div><!--   Close Container     -->

<script>
    $(".buyProductFinal").on("click", function(){
        var productId = $(".product").attr("data-productId");
        var customerName = $("#customerFullName").val();
        var customerEmail = $("#customerEmail").val();
        var customerPassword = $("#customerPassword").val();
        var customerAddress = $("#customerAddress").val();
        var customerCity = $("#customerCity").val();
        var customerPostcode = $("#customerPostcode").val();
        var customerPhone = $("#customerPhone").val();
        $.get("manage-products.php?sFunction=buyProduct&productId="+productId+"&customerName="+customerName+"&customerEmail="+customerEmail+"&customerPassword="+customerPassword+"&customerAddress="+customerAddress+"&customerCity="+customerCity+"&customerPostcode="+customerPostcode+"&customerPhone="+customerPhone, function(data){
            var oData = JSON.parse(data);
            console.log(oData);
            if(oData.response == "success"){
                $(".alerts").empty();
                $(".alerts").prepend('<div class="alert alert-success" role="alert">Your order has been placed. Check you email for more details.</div>');
            }else{
                $(".alerts").empty();
                $(".alerts").prepend('<div class="alert alert-danger" role="alert">The product could not be ordered.</div>');
            }
        });
    });

</script>