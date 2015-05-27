<?php
include("mainHeader.php");
?>

<!--end of jumbotron-->
<div class="container">
    <div class="orderBy">
        <ul class="nav nav-tabs">
            <li role="presentation" class="disabled"><a>Order by: </a></li>
            <li id="orderByName" class="active activeOrderBy" role="presentation"><a>Name</a></li>
            <li id="orderByPrice" class="activeOrderBy" role="presentation"><a>Price</a></li>
            <li id="orderByDate" class="activeOrderBy" role="presentation"><a>Date</a></li>
        </ul>
        <br>

        <div class="date" style="display: none">
            <ol class="breadcrumb">
                <li class="asc">ASC</li>
                <li class="desc"><a>DESC</a></li>
            </ol>
        </div>
        <div class="slider" style="display: none">
            <p>
                <label for="amount">Maximum price:</label>
                <input type="text" id="amount" readonly style="border:0; color:#f6931f; font-weight:bold;">
            </p>

            <div id="slider-range-min"></div>
            <br>
        </div>
    </div>
    <div class="products">
        <?php
        displayProducts(1, "product_name");
        ?>
    </div>
</div><!--  End of container   -->

</body>
</html>

<script>
    setInterval(function () {
        $.get("manage-products.php?sFunction=refreshProductsTable");
    }, 43200000);


    //maybe can be done with switch
    $("#orderByName").on("click", function () {
        $(".active.activeOrderBy").removeClass("active");
        $(this).addClass("active");
        $(".slider").hide();
        $(".date").hide();
        $(".products").empty();
        //orderByProduct("product_name");
        $.get("manage-products.php?sFunction=orderByProduct&orderBy=product_name", function (data) {
            $(".products").append(data);
        });
    });

    $("#orderByPrice").on("click", function () {
        $(".active.activeOrderBy").removeClass("active");
        $(this).addClass("active");
        $(".products").empty();
        $(".date").hide();
        $(".slider").show("slow");
        $.get("manage-products.php?sFunction=orderByProduct&orderBy=product_price", function (data) {
            $(".products").append(data);
        });
    });
    $(function () {
        $("#slider-range-min").slider({
            range: "min",
            value: 1,
            min: 18,
            max: 5000,
            slide: function (event, ui) {
                $("#amount").val("$" + ui.value);
                var sliderValue = ui.value;
                $.get("manage-products.php?sFunction=orderBySliderProduct&sliderValue=" + sliderValue, function (data) {
                    $(".products").empty();
                    $(".products").append(data);
                });
            }
        });
        $("#amount").val("$" + $("#slider-range-min").slider("value"));
    });

    $("#orderByDate").on("click", function () {
        $(".active.activeOrderBy").removeClass("active");
        $(this).addClass("active");
        $(".slider").hide();
        $(".date").show("slow");
        $(".products").empty();
        $.get("manage-products.php?sFunction=orderByProduct&orderBy=product_date", function (data) {
            $(".products").append(data);
        });
    });
    $(document).on("click", ".asc a", function () {
        $(".products").empty();
        $(".asc").html('ASC');
        $(".desc").html('<a>DESC</a>');
        $.get("manage-products.php?sFunction=orderByProduct&orderBy=product_date", function (data) {
            $(".products").append(data);
        });
    });
    $(document).on("click", ".desc a", function () {
        $(".products").empty();
        $(".desc").html('DESC');
        $(".asc").html('<a>ASC</a>');
        $.get("manage-products.php?sFunction=orderByProduct&orderBy=product_date%20DESC", function (data) {
            $(".products").append(data);
        });
    });


    $(document).on("click", ".buyProduct", function () {
        buyProduct($(this).parent().parent().parent().parent().attr("data-productId"));


    });
    function buyProduct(id) {
        window.location.replace("buy-product.php?id=" + id);
    }


</script>