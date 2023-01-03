<?php
session_start();
include("../../db.php");
include("temp_user.php");

if (isset($_GET['action']) && $_GET['action'] != "" && $_GET['action'] == 'delete') {
  $user_id = $_GET['user_id'];
  /*this is delete query*/
  $get_order_id = mysqli_query($con, "select order_id from orders_info where user_id='$user_id'") or die("query 2 incorrect.......");
  if (mysqli_num_rows($get_order_id) > 0) {
    while (list($order_id) = mysqli_fetch_array($get_order_id)) {
      mysqli_query($con, "delete from orders_info where order_id='$order_id'") or die("query is incorrect...");
      mysqli_query($con, "delete from order_products where order_id='$order_id'") or die("query is incorrect...");
    }
  }

  $get_comment_id = mysqli_query($con, "select Id from comment where User_id='$user_id'") or die("query 2 incorrect.......");
  if (mysqli_num_rows($get_comment_id) > 0) {
    while (list($comment_id) = mysqli_fetch_array($get_comment_id)) {
      mysqli_query($con, "delete from comment where Id='$comment_id'") or die("query is incorrect...");
    }
  }
  mysqli_query($con, "delete from user_info where user_id='$user_id'") or die("query is incorrect...");
  header("location: manageuser.php?page=1");
}

//pagination

$page = $_GET['page'];
$paging = mysqli_query($con, "select user_id from user_info");
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
          <h4 class="card-title">Manage User</h4>
        </div>
        <div class="card-body">
          <div class="table-responsive ps">
            <table class="table tablesorter table-hover" id="">
              <thead class=" text-primary">
                <tr>
                  <th>ID</th>
                  <th>First name</th>
                  <th>Last name</th>
                  <th>Email</th>


                  <th>Mobile</th>
                  <th>City</th>
                  <th>Address</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $result = mysqli_query($con, "select user_id,first_name,last_name, email, mobile,address1,address2 from user_info") or die("query 2 incorrect.......");

                $j = 0;
                $listUser = array();
                while (list($user_id, $user_name, $user_last, $email,  $mobile, $address1, $address2) = mysqli_fetch_array($result)) {
                  $tempUser = new TempUser($user_id, $user_name, $user_last, $email,  $mobile, $address1, $address2);
                  $listUser[$j] = $tempUser;
                  $j++;
                }

                for ($i = $start; $i < $end; $i++) {
                  $user_id = $listUser[$i]->user_id;
                  $user_name = $listUser[$i]->first_name;
                  $user_last = $listUser[$i]->last_name;
                  $email = $listUser[$i]->email;
                  $mobile = $listUser[$i]->mobile;
                  $address1 = $listUser[$i]->address1;
                  $address2 = $listUser[$i]->address2;
                  echo "<tr>
                        <td>$user_id</td>
                         <td>$user_name</td>
                          <td>$user_last</td>
                           <td>$email</td>
                         <td>$mobile</td>
                          <td>$address1</td>
                           <td>$address2</td>";
                  echo "<td>
                     
                        <a class='btn btn-danger' href='manageuser.php?user_id=$user_id&action=delete'>Delete<div class='ripple-container'></div></a>
                        </td></tr>";
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
            <a class="page-link" href="manageuser.php?page=<?php echo $prevpage; ?>" aria-label="Previous">
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
              <li class="page-item active"><a class="page-link" href="manageuser.php?page=<?php echo $b; ?>"><?php echo $b . " "; ?></a></li>
            <?php
            } else {
            ?>
              <li class="page-item"><a class="page-link" href="manageuser.php?page=<?php echo $b; ?>"><?php echo $b . " "; ?></a></li>
          <?php
            }
          }
          ?>
          <li class="page-item">
            <a class="page-link" href="manageuser.php?page=<?php echo $nextpage; ?>" aria-label="Next">
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