<?php
include("mainHeader.php");
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Webshop</title>
</head>
<body>
    <div class="container">
        <h3>Create Account</h3>
        <div class="row">
            <div class="col-lg-6">
                <form role="form" action="add-partner.php" method="post">
                    <div class="form-group">
                        <label>Full Name: </label>
                        <input type="text" class="form-control" placeholder="Insert your full name..." name="partnerFullName">
                        </div>
                        <div class="form-group">
                            <label>Company Name: </label>
                            <input type="text" class="form-control" placeholder="Insert your company name..." name="partnerCompanyName">
                        </div>
                    <div class="form-group">
                        <label>Email: </label>
                        <input type="email" class="form-control" placeholder="Insert your email..." name="partnerEmail">
                        </div>
                        <div class="form-group">
                            <label>Password: </label>
                            <input type="password" class="form-control" placeholder="Insert your password..." name="partnerPassword">
                        </div>
                    <div class="form-group">
                        <label>Retype Password: </label>
                        <input type="password" class="form-control" placeholder="Retype your password..." name="partnerPassword">
                    </div>

                        <input class="btn btn-primary" type="submit" name="submit" value="Create Account">
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>







