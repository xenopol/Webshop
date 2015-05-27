<?php
include_once("conn.php");
include ("functions.php");
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="icon"
          type="image/png"
          href="favicon.ico">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Webshop</title>

    <link rel="stylesheet" href="style.css">
    <!-- Bootstrap -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap-theme.min.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/themes/smoothness/jquery-ui.css" />
    <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/jquery-ui.min.js"></script>


    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
<nav class="navbar navbar-inverse" role="navigation">
    <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="index.php">-::Webshop::-</a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <form class="navbar-form navbar-left" role="search">
                <div class="form-group">
                    <input id="searchInput" type="text" class="form-control" placeholder="Search">
                </div>
                <input type="button" class="searchBtn btn btn-default" value="Search">
            </form>
            <ul class="nav navbar-nav navbar-right">
                <?php
                if($_SESSION["partner"]){
                    ?>
                    <li><a id="welcome">Welcome <?php echo $_SESSION["partner"]["name"]; ?></a></li>
                    <?php
                    if($_SESSION["partner"]["admin"]==1){// if admin is logged in
                        ?>
                        <!--Backend dropdown-->
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Backend <span class="caret"></span></a>
                            <ul class="dropdown-menu" role="menu">
                                <li><a id="backend" href="admin-backend.php">My Products</a></li>
                                <li><a class="addProductLink" href="add-product.php">Add Product</a></li>
                                <li class="divider"></li>
                                <li><a class="seeAllPartners" href="see-all-partners.php">Partners</a></li>
                                <li class="divider"></li>
                                <li><a class="seeAllCustomers" href="see-all-customers.php">Customers</a></li>
                                <li class="divider"></li>
                                <li><a class="seeAllOrders" href="see-all-orders.php">Orders</a></li>
                            </ul>
                        </li>

                        <?php
                    }else{
                        ?>
                        <li><a id="becomePartner" href="become-partner.php">Upload Url</a></li>
                        <?php
                    }
                    ?>
                    <li><a id="logout">Logout</a></li>
                <?php
                }else if($_SESSION["customer"]){
                    ?>
                    <li><a id="welcome">Welcome <?php echo $_SESSION["customer"]["name"]; ?></a></li>
                    <li><a id="logout">Logout</a></li>
                <?php
                }else{
                    ?>
                    <li><a id="loginOut" data-toggle="modal" data-target="#loginModal">Login</a></li>
                    <li><a id="signupOut" href="create-account.php">Become partner</a></li>
                <?php
                }
                ?>
            </ul>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>

<!--Create account modal-->
<div class="modal fade" id="createAccountModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">Create Account</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal loginForm" role="form">
                    <div class="form-group">
                        <label for="createFullName" class="col-sm-2 control-label">Full Name</label>
                        <div class="col-sm-10">
                            <input type="test" class="form-control" id="createFullName" placeholder="Full Name">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="createEmail" class="col-sm-2 control-label">Email</label>
                        <div class="col-sm-10">
                            <input type="email" class="form-control" id="createEmail" placeholder="Email" autofocus>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="createPassword" class="col-sm-2 control-label">Password</label>
                        <div class="col-sm-10">
                            <input type="password" class="form-control" id="createPassword" placeholder="Password">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="createRetypePassword" class="col-sm-2 control-label">Retype Password</label>
                        <div class="col-sm-10">
                            <input type="password" class="form-control" id="createRetypePassword" placeholder="Retype Password">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button id="createAccountBtnFinal" type="button" class="btn btn-primary">Create Account</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<!-- Login modal -->
<div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog loginModalDiv">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="myModalLabel">Login</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal loginForm" role="form">
                    <div class="form-group">
                        <label for="loginEmail" class="col-sm-2 control-label">Email</label>
                        <div class="col-sm-10">
                            <input type="email" class="form-control" id="loginEmail" placeholder="Email" autofocus>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="loginPassword" class="col-sm-2 control-label">Password</label>
                        <div class="col-sm-10">
                            <input type="password" class="form-control" id="loginPassword" placeholder="Password">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button id="loginBtnFinal" type="button" class="btn btn-primary">Login</button>
            </div>
        </div>
    </div>
</div>


<script>
    $("#loginBtnFinal").on("click", function(){
        var sEmail = $("#loginEmail").val();
        var sPassword = $("#loginPassword").val();
        $.get("login.php?sFunction=login&email="+sEmail+"&password="+sPassword, function(sData){
            //console.log(sData);
            var oData = JSON.parse(sData);
            //console.log(oData);
            if(oData.response == "partner" || oData.response == "customer"){
                console.log("works");
                location.reload();
            }else{
                console.log("error");
                $(".loginModalDiv").effect("shake");
            }
        });
    });

    $("#logout").on("click", function () {
        $.get("login.php?sFunction=logout", function() {
            window.location.replace("index.php");
        });
    });

    $("#searchInput").on("keyup",function () {
        var searchInput = $("#searchInput").val();
        console.log(searchInput);
        $(".products").empty();
        $.get("manage-products.php?sFunction=searchProduct&searchInput="+searchInput, function (data) {
            $(".products").append(data);
        });
    });

</script>