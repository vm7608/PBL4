<!-- quản lý danh sách product -->
<?php
session_start();
include("../../db.php");
include("temp_product.php");
if (isset($_GET['action']) && $_GET['action'] != "" && $_GET['action'] == 'delete') {
  //delete product
  $product_id = $_GET['product_id'];
  mysqli_query($con, "delete from order_products where product_id='$product_id'") or die("query is incorrect...");
  mysqli_query($con, "delete from products where product_id='$product_id'") or die("query is incorrect...");
  header("location: sumit_form.php?success=1");
}

//pagination

$page = $_GET['page'];
$paging = mysqli_query($con, "select product_id from products");
$count = mysqli_num_rows($paging);
if ($page == "" || $page == "1") {
  $start = 0;
} else {
  $start = ($page * 10) - 10;
}
$end = $start + 10;
if ($end > $count) {
  $end = $count;
}
$prevpage = $page - 1;
$nextpage = $page + 1;
if ($page == 1) {
  $prevpage = 1;
}
$numOfPage = $count / 10 + 1;
if ($nextpage > $numOfPage) {
  $nextpage = $page;
}
include "sidenav.php";
include "topheader.php";
?>
<!-- End Navbar -->
<div class="content">
  <div class="container-fluid">
    <div class="col-md-14">

      <div class="panel-body">
        <a>
          <?php  //success message
          if (isset($_POST['success'])) {
            $success = $_POST["success"];
            echo "<div class='col-md-12 col-xs-12' id='product_msg'>
          <div class='alert alert-success'>
            <a href='#'' class='close' data-dismiss='alert' aria-label='close'>×</a>
            <b>Process successfully!</b>
          </div>
        </div>";
          }
          ?></a>
      </div>

      <div class="card ">
        <div class="card-header card-header-primary">
          <h4 class="card-title"> Products List</h4>
        </div>
        <div class="card-body">
          <div class="table-responsive ps">
            <table class="table tablesorter " id="page1">
              <thead class=" text-primary">
                <tr>
                  <th>ID</th>
                  <th>Image</th>
                  <th>Name</th>
                  <th>Price</th>
                  <th></th>
                  <th>
                    <a class=" btn btn-primary" href="add_products.php">Add New</a>
                  </th>
                </tr>
              </thead>
              <tbody>
                <?php
                $result = mysqli_query($con, "select product_id,product_image, product_title,product_price from products") or die("query 1 incorrect.....");
                $j = 0;
                $listProduct = array();
                while (list($product_id, $image, $product_name, $price) = mysqli_fetch_array($result)) {
                  $tempProduct = new TempProduct($product_id, $image, $product_name, $price);
                  $listProduct[$j] = $tempProduct;
                  $j++;
                }

                for ($i = $start; $i < $end; $i++) {
                  $image = $listProduct[$i]->image;
                  $product_name = $listProduct[$i]->product_name;
                  $price = $listProduct[$i]->price;
                  $product_id = $listProduct[$i]->product_id;
                  echo "<tr>
                        <td>$product_id</td>
                        <td><img src='../../product_images/$image' style='width:50px; height:50px; border:groove #000'></td><td>$product_name</td>
                        <td>$price</td>
                        <td>
                        <a class=' btn btn-primary' style='background-color:#298767;' href='edit_products.php?proid=$product_id'>Edit</a>
                        </td>
                        <td>
                        <a class=' btn btn-danger' href='products_list.php?product_id=$product_id&action=delete'>Delete</a>
                        </td>
                        </tr>";
                }

                ?>
              </tbody>
            </table>
            <div class="ps__rail-x" style="left: 0px; bottom: 0px;">
              <div class="ps__thumb-x" tabindex="0" style="left: 0px; width: 0px;"></div>
            </div>
            <div class="ps__rail-y" style="top: 0px; right: 0px;">
              <div class="ps__thumb-y" tabindex="0" style="top: 0px; height: 0px;"></div>
            </div>
          </div>
        </div>
      </div>
      <nav aria-label="Page navigation example">
        <ul class="pagination">
          <li class="page-item">
            <a class="page-link" href="products_list.php?page=<?php echo $prevpage; ?>" aria-label="Previous">
              <span aria-hidden="true">&laquo;</span>
              <span class="sr-only">Previous</span>
            </a>
          </li>
          <?php
          //counting paging
          $a = $count / 10;
          $a = ceil($a);

          for ($b = 1; $b <= $a; $b++) {

            if ($b == $page) {
          ?>
              <li class="page-item active"><a class="page-link" href="products_list.php?page=<?php echo $b; ?>"><?php echo $b . " "; ?></a></li>
            <?php
            } else {
            ?>
              <li class="page-item"><a class="page-link" href="products_list.php?page=<?php echo $b; ?>"><?php echo $b . " "; ?></a></li>
          <?php
            }
          }
          ?>
          <li class="page-item">
            <a class="page-link" href="products_list.php?page=<?php echo $nextpage; ?>" aria-label="Next">
              <span aria-hidden="true">&raquo;</span>
              <span class="sr-only">Next</span>
            </a>
          </li>
        </ul>
      </nav>
    </div>
  </div>
</div>
<?php
include "footer.php";
?>