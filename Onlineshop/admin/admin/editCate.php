<!-- Chỉnh sửa category -->
<?php
session_start();
include("../../db.php");

$cat_id = $_REQUEST['cid'];
if (isset($_POST['btn_save'])) {
    $cat_title = $_POST['title'];
    $query = "UPDATE categories SET cat_title = '$cat_title' WHERE cat_id = '$cat_id'";
    $result = mysqli_query($con, $query);
    if ($result) {
        header("location: manageCate.php?page=1");
    } else {
        echo "Error";
    }
}
$result = mysqli_query($con, "select cat_title from categories where cat_id = '$cat_id'") or die("query 1 incorrect.....");
list($cat_title) = mysqli_fetch_array($result);


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
                                        <input type="text" id="tags" name="title" required class="form-control" value="<?php echo $cat_title; ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" id="btn_save" name="btn_save" required class="btn btn-fill btn-primary">Save</button>
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