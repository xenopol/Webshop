<?php
include ("mainHeader.php");
$productId = $_GET["id"];

$sql = "SELECT * FROM products WHERE product_id='$productId'";
$result = $conn->query($sql);

if ($conn->affected_rows>0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
       ?>
        <div class="container">
            <div class="alerts"></div>
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Edit Product</h3>
                </div>
                <div class="panel-body">
                    <div class="col-lg-5"><img class="img-responsive img-rounded" src="<?php echo $row["product_image"] ?>"></div>
                    <div class="col-lg-7 product" data-productId="<?php echo $productId ?>">
                        <form class="form-horizontal" role="form">
                            <div class="form-group">
                                <label for="inputEmail3" class="col-sm-2 control-label">Product Name</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="productName" value="<?php echo $row["product_name"]; ?>" placeholder="Product name">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputPassword3" class="col-sm-2 control-label">Product Description</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="productDescription" value="<?php echo $row["product_description"]; ?>" placeholder="Product description">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputPassword3" class="col-sm-2 control-label">Product Price</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="productPrice" value="<?php echo $row["product_price"]; ?>" placeholder="Product price">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputPassword3" class="col-sm-2 control-label">Product Image</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="productImage" value="<?php echo $row["product_image"]; ?>" placeholder="Product image">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputPassword3" class="col-sm-2 control-label">Product Quantity</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="productQuantity" value="<?php echo $row["product_quantity"]; ?>" placeholder="Product quantity">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-10 col-sm-offset-2">
                                    <?php
                                    if($row["product_quantity"]<0){
                                        ?>
                                        <span class="label label-danger">Out of stock</span>
                                        <?php
                                    }
                                    ?>
                                </div>
                            </div>
                            <button type="button" class="btn btn-primary pull-right editProductFinal">Edit Product</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
<?php
    }
}
?>
<script>
    $(".editProductFinal").on("click", function () {
        var productId = $(".product").attr("data-productId");
        var productName = $("#productName").val();
        var productDescription = $("#productDescription").val();
        var productPrice = $("#productPrice").val();
        var productImage = $("#productImage").val();
        var productQuantity = $("#productQuantity").val();
        console.log(productId, productDescription, productImage, productName, productPrice);
        $.get("manage-products.php?sFunction=editProduct&productId="+productId+"&productName="+productName+"&productDescription="+productDescription+"&productPrice="+productPrice+"&productImage="+productImage+"&productQuantity="+productQuantity, function (data) {
            var oData = JSON.parse(data);
            if(oData.response == "success"){
                $(".alerts").empty();
                $(".alerts").prepend('<div class="alert alert-success" role="alert">The product has been edited.</div>');
            }else{
                $(".alerts").empty();
                $(".alerts").prepend('<div class="alert alert-danger" role="alert">The product couldn\'t be edited.</div>');
            }
        console.log(JSON.parse(data));
        });
    });
</script>

