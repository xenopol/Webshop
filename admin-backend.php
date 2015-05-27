<?php
include_once("mainHeader.php");

?>

<div class="container">
    <button type="button" class="btn btn-success btn-lg">Total Income: <?php totalIncome(); ?> DKK </button>
    <div class="products">
        <?php
            displayMyProducts();
        ?>
    </div>
</div>

<script>
    //edit product
    $(".editProduct").on("click", function () {
        editProduct($(this).parent().parent().parent().parent().attr("data-productId"));
    });
    function editProduct(id){
        var productId = id;
        window.location.replace("edit-product.php?id="+productId);
    }

    //delete product
    $(".deleteProduct").on("click", function () {
        deleteProduct($(this).parent().parent().parent().parent().attr("data-productId"));
    });
    function deleteProduct(id){
        var productId = id;
        $.get("manage-products.php?sFunction=deleteProduct&productId="+productId, function (data) {
            var oData = JSON.parse(data);
            if(oData.response=="success"){
                location.reload();

            }else{
                console.log("error");
            }
        });
    }

</script>



