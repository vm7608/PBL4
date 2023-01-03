<!-- Thêm category -->
<?php
session_start();
include("../../db.php");
if (isset($_POST['btn_save'])) {
    $cat_title = $_POST['title'];
    $query = "INSERT INTO categories (cat_title) VALUES ('$cat_title')";
    $result = mysqli_query($con, $query);
    if ($result) {
        header("location: manageCate.php?page=1");
    } else {
        echo "Error";
    }
}
include "sidenav.php";
include "topheader.php";
?>
<!-- End Navbar -->
<div class="content">
    <div class="container-fluid">
        <form action="" method="post" type="form" name="form" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-7">
                    <div class="card">
                        <div class="card-header card-header-primary">
                            <h5 class="title">Add Categories</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Category title</title></label>
                                        <input type="text" id="tags" name="title" required class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" id="btn_save" name="btn_save" required class="btn btn-fill btn-primary">Add</button>
                        </div>
                    </div>
                </div>

            </div>
        </form>

    </div>
</div>
<?php
include "footer.php";
?>