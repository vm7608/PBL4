<!-- Xử lí thêm sản phẩm -->
<?php
session_start();
include("../../db.php");


if (isset($_POST['btn_save'])) {
  $product_name = $_POST['product_name'];
  $details = $_POST['details'];
  $price = $_POST['price'];
  $c_price = $_POST['c_price'];
  $product_type = $_POST['product_type'];
  $brand = $_POST['brand'];
  $tags = $_POST['tags'];

  //picture coding
  $picture_name = $_FILES['picture']['name'];
  $picture_type = $_FILES['picture']['type'];
  $picture_tmp_name = $_FILES['picture']['tmp_name'];
  $picture_size = $_FILES['picture']['size'];

  if ($picture_type == "image/jpeg" || $picture_type == "image/jpg" || $picture_type == "image/png" || $picture_type == "image/gif") {

    // Chỗ này đang lỗi file ko được up vào folder, có thể do ko có quyền, nếu lên linux lại càng có thể lỗi nữa
    if ($picture_size <= 5000000) {
      $pic_name = time() . "_" . $picture_name;
      // move_uploaded_file($picture_tmp_name,"../product_images/".$pic_name);
      //nhớ đổi path khi chuyen qua linux
      move_uploaded_file($picture_tmp_name, "../../product_images/" . $pic_name);
      mysqli_query($con, "insert into products (product_cat, product_brand,product_title,product_price, product_desc, product_image,product_keywords) values ('$product_type','$brand','$product_name','$price','$details','$pic_name','$tags')") or die("query incorrect");
      header("location: sumit_form.php?success=1");
    }

    mysqli_close($con);
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
              <h5 class="title">Add Product</h5>
            </div>
            <div class="card-body">

              <div class="row">

                <div class="col-md-12">
                  <div class="form-group">
                    <label>Product Title</label>
                    <input type="text" id="product_name" required name="product_name" class="form-control">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="">
                    <label for="">Add Image</label>
                    <input type="file" name="picture" required class="btn btn-fill btn-success" id="picture">
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="form-group">
                    <label>Description</label>
                    <textarea rows="4" cols="80" id="details" required name="details" class="form-control"></textarea>
                  </div>
                </div>

                <div class="col-md-12">
                  <div class="form-group">
                    <label>Pricing</label>
                    <input type="text" id="price" name="price" required class="form-control">
                  </div>
                </div>
              </div>



            </div>

          </div>
        </div>
        <div class="col-md-5">
          <div class="card">
            <div class="card-header card-header-primary">
              <h5 class="title">Category/Brand</h5>
            </div>
            <div class="card-body">

              <div class="row">

                <div class="col-md-12">
                  <div class="form-group">
                    <label for="">Product Category</label>
                    <select name="product_type" class="form-control">
                      <?php
                      $result = mysqli_query($con, "select * from categories") or die("query 1 incorrect.....");
                      while (list($cat_id, $cat_title) = mysqli_fetch_array($result)) {
                        if ($cat_id == $product_cat) {
                          echo
                          "<option style='background-color:#1a2035;' name='product_type' value=" . $cat_id . " selected>" . $cat_title . "</option>";
                        } else {
                          echo "<option style='background-color:#1a2035;' name='product_type' value=" . $cat_id . ">" . $cat_title . "</option>";
                        }
                      }
                      ?>
                    </select>
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="form-group">
                    <label for="">Product Brand</label>
                    <!-- <input type="number" id="brand" name="brand" required class="form-control"> -->
                    <select name="brand" class="form-control">
                      <?php
                      $result = mysqli_query($con, "select * from brands") or die("query 1 incorrect.....");
                      while (list($brand_id, $brand_title) = mysqli_fetch_array($result)) {

                        if ($brand_id == $product_brand) {
                          echo "<option style='background-color:#1a2035;' name='brand' value=" . $brand_id . " selected>" . $brand_title . "</option>";
                        } else {
                          echo "<option style='background-color:#1a2035;' name='brand' value=" . $brand_id . ">" . $brand_title . "</option>";
                        }
                      }
                      ?>
                    </select>
                  </div>
                </div>


                <div class="col-md-12">
                  <div class="form-group">
                    <label>Product Keywords</label>
                    <input type="text" id="tags" name="tags" required class="form-control">
                  </div>
                </div>
              </div>

            </div>
            <div class="card-footer">
              <button type="submit" id="btn_save" name="btn_save" required class="btn btn-fill btn-primary">Add Product</button>
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