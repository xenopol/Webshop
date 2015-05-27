<?php
    include("mainHeader.php");
?>
<div class="container">
    <div class="alerts"></div>
    <h3>Upload URL</h3><br>
    <div class="row">
        <div class="col-lg-6">
            <div class="form-group">
                <label>API URL: </label>
                <input id="apiUrl" class="form-control" type="url" placeholder="Insert your API's URL..." name="partnerApiUrl">
            </div>
            <div class="form-group">
                <label>Commission: </label>
                <input id="commission" class="form-control" type="text" placeholder="20 %" name="partnerCommission">
            </div>
            <input class="btn btn-primary" id="becomePartnerBtn" type="button" value="Become partner">
        </div>
    </div>
</div>
<script>
    $("#becomePartnerBtn").on("click", function () {
        var sApiUrl = $("#apiUrl").val();
        var sCommission = $("#commission").val();
        $.get("login.php?sFunction=becomePartner&apiUrl="+sApiUrl+"&commission="+sCommission, function (data) {
            var oData = JSON.parse(data);
            if(oData.response == "success"){
                console.log(oData.response);
                $(".alerts").empty();
                $(".alerts").prepend('<div class="alert alert-success" role="alert">You have become a partner. Your products have been added, you can see it on the front page.</div>');
                setInterval(function () {
                    location.replace("index.php");
                }, 2000);
            }else{
                $(".alerts").empty();
                $(".alerts").prepend('<div class="alert alert-danger" role="alert">There has been an error with the information you have entered, try again.</div>');
            }
        });
    });
</script>