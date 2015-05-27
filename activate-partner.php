<?php
include ("conn.php");
include ("mainHeader.php");

$loginCode = $_GET["loginCode"];

$sql = "UPDATE partners SET partner_active = '1' WHERE partner_key='$loginCode'";
$result = $conn->query($sql);
?>
<div class="container">
    <h1>Your account has been activated, try to login.</h1>
</div>

<script>
    setInterval(function (){
        location.replace("index.php");
    }, 2000);
</script>