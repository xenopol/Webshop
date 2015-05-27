<?php
    include("mainHeader.php");
?>
<div class="container">
    <div class="alerts"></div>
    <div class="row">
        <div class="col-lg-6">
            <h3>Add Product</h3><br>
            <form role="form">
                <div class="form-group">
                    <label>Product name</label>
                    <input type="text" class="form-control" id="productName" placeholder="Insert product name...">
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <input type="text" class="form-control" id="productDescription" placeholder="Insert product description...">
                </div>
                <div class="form-group">
                    <label>Product Price</label>
                    <input type="text" class="form-control" id="productPrice" placeholder="Insert product price...">
                </div>
                <div class="form-group">
                    <label>Product Image</label>
                    <input type="text" class="form-control" id="productImage" placeholder="Insert product image URL...">
                </div>
                <input id="addProductBtn" class="btn btn-success" type="button" value="Add Product">
            </form>
        </div>
    </div>
</div>


<script>
    $("#addProductBtn").on("click", function () {
        var productName = $("#productName").val();
        var productDescription = $("#productDescription").val();
        var productPrice = $("#productPrice").val();
        var productImage = $("#productImage").val();

        $.get("manage-products.php?sFunction=addProduct&productName="+productName+"&productDescription="+productDescription+"&productPrice="+productPrice+"&productImage="+productImage, function (data) {
            var oData = JSON.parse(data);
            if(oData.response == "success"){
                $(".alerts").empty();
                $(".alerts").prepend('<div class="alert alert-success" role="alert">The product has been added.</div>');
            }else{
                $(".alerts").empty();
                $(".alerts").prepend('<div class="alert alert-danger" role="alert">The product could not be added.</div>');
            }

        });
    });

</script>
