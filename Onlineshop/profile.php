<?php
include "header.php";
?>
<link href="./css/profile.css" rel="stylesheet">
<div class="row" style="margin: 30px; border-radius: 10px; background-color: white">
    <div class="col-md-3" style="background-color: white;">
        <div style="height: 30px; border-bottom: 2px solid black">
        </div>
        <div>
            <div class="list-btn-profile">
                <div class="btn-profile" index="1">Home</div>
                <div class="btn-profile" index="3">Order</div>
                <div class="btn-profile" index="4">Bill</div>
            </div>
        </div>
    </div>
    <div class="col-md-9" style="background-color: white; border-left: 2px solid black;padding-bottom: 30px;">
        <div class="content" id="get-content">
            <!-- Get content in here by ajax -->
            
        </div>
    </div>
</div>
<?php
include "footer.php";
?>

