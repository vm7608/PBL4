<!-- Quản lý các nhãn hàng -->
<?php
session_start();
include("../../db.php");
include("temp_brand.php");
if (isset($_GET['action']) && $_GET['action'] != "" && $_GET['action'] == 'delete') {
    $brand_id = $_GET['bid'];
    $result = mysqli_query($con, "select product_id from products where product_brand = '$brand_id'") or die("query 2 incorrect.......");
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_array($result)) {
            $product_id = $row['product_id'];
            mysqli_query($con, "delete from order_products where product_id='$product_id'") or die("query is incorrect...");
            mysqli_query($con, "delete from products where product_id='$product_id'") or die("query is incorrect...");
        }
    }
    mysqli_query($con, "delete from brands where brand_id='$brand_id'") or die("query is incorrect...");
    header("location: manageBrand.php?page=1");
}

//pagination
$page = $_GET['page'];
$paging = mysqli_query($con, "select brand_id from brands");
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
            <div class="card ">
                <div class="card-header card-header-primary">
                    <h4 class="card-title">Manage Brands</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive ps">
                        <table class="table tablesorter table-hover" id="">
                            <thead class=" text-primary">
                                <tr>
                                    <th>ID</th>
                                    <th>Brand</th>
                                    <th>Number of products</th>
                                    <th></th>
                                    <th>
                                        <a class=" btn btn-primary" href="addBrand.php">Add New</a>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $result = mysqli_query($con, "select * from brands") or die("query 2 incorrect.......");
                                $j = 0;
                                $listBrand = array();
                                while (list($brand_id, $brand_title) = mysqli_fetch_array($result)) {
                                    $sql = "SELECT COUNT(*) AS count_items FROM products WHERE product_brand=$brand_id";
                                    $query = mysqli_query($con, $sql);
                                    $row = mysqli_fetch_array($query);
                                    $countProduct = $row["count_items"];
                                    $tempBrand = new TempBrand($brand_id, $brand_title, $countProduct);
                                    $listBrand[$j] = $tempBrand;
                                    $j++;
                                }

                                for ($i = $start; $i < $end; $i++) {
                                    $brand_id = $listBrand[$i]->brand_id;
                                    $brand_title = $listBrand[$i]->brand_title;
                                    $num = $listBrand[$i]->numOfProduct;
                                    echo "<tr>
                                            <td>$brand_id</td>
                                            <td>$brand_title</td>
                                            <td>$num</td>
                                            ";
                                    echo "<td>
                                        <a class=' btn btn-primary' style='background-color:#298767;' href='editBrand.php?bid=$brand_id'>Edit</a>
                                        </td>
                                        <td>
                                        <a class=' btn btn-danger' href='manageBrand.php?bid=$brand_id&action=delete'>Delete</a>
                                        </td>
                                        </tr>";
                                }
                                mysqli_close($con);
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
                        <a class="page-link" href="manageBrand.php?page=<?php echo $prevpage; ?>" aria-label="Previous">
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
                            <li class="page-item active"><a class="page-link" href="manageBrand.php?page=<?php echo $b; ?>"><?php echo $b . " "; ?></a></li>
                        <?php
                        } else {
                        ?>
                            <li class="page-item"><a class="page-link" href="manageBrand.php?page=<?php echo $b; ?>"><?php echo $b . " "; ?></a></li>
                    <?php
                        }
                    }
                    ?>
                    <li class="page-item">
                        <a class="page-link" href="manageBrand.php?page=<?php echo $nextpage; ?>" aria-label="Next">
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