  <!-- Thống kê tổng số người dùng, sản phẩm, đơn hàng, category, brand, order -->
  <?php
    include("../../db.php");

    ?>

  <div class="row" style="padding-top: 10vh;">
      <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
          <div class="card card-stats">
              <div class="card-header card-header-primary card-header-icon">
                  <div class="card-icon">
                      <i class="material-icons">content_copy</i>
                  </div>
                  <p class="card-category">Total users</p>
                  <h3 class="card-title">
                      <?php $query = "SELECT user_id FROM user_info";
                        $result = mysqli_query($con, $query);
                        if ($result) {
                            // it return number of rows in the table. 
                            $row = mysqli_num_rows($result);

                            printf(" " . $row);

                            // close the result. 
                        }  ?>
                  </h3>
              </div>

          </div>
      </div>
      <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
          <div class="card card-stats">
              <div class="card-header card-header-danger card-header-icon">
                  <div class="card-icon">
                      <i class="material-icons">info_outline</i>
                  </div>
                  <p class="card-category">Total Product</p>
                  <h3 class="card-title"><?php $query = "SELECT product_id FROM products";
                                            $result = mysqli_query($con, $query);
                                            if ($result) {
                                                // it return number of rows in the table. 
                                                $row = mysqli_num_rows($result);

                                                printf(" " . $row);

                                                // close the result. 
                                            } ?></h3>
              </div>

          </div>
      </div>
      <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
          <div class="card card-stats">
              <div class="card-header card-header-success card-header-icon">
                  <div class="card-icon">
                      <i class="material-icons">store</i>
                  </div>
                  <p class="card-category">Total Categories</p>
                  <h3 class="card-title"> <?php $query = "SELECT cat_id FROM categories";
                                            $result = mysqli_query($con, $query);
                                            if ($result) {
                                                // it return number of rows in the table. 
                                                $row = mysqli_num_rows($result);

                                                printf(" " . $row);

                                                // close the result. 
                                            } ?></h3>
              </div>

          </div>
      </div>
      <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
          <div class="card card-stats">
              <div class="card-header card-header-secondary card-header-icon">
                  <div class="card-icon">
                      <i class="material-icons">tag</i>
                  </div>
                  <p class="card-category">Total Brand</p>
                  <h3 class="card-title"> <?php $query = "SELECT brand_id FROM brands";
                                            $result = mysqli_query($con, $query);
                                            if ($result) {
                                                // it return number of rows in the table. 
                                                $row = mysqli_num_rows($result);

                                                printf(" " . $row);

                                                // close the result. 
                                            } ?></h3>
              </div>

          </div>
      </div>

      <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
          <div class="card card-stats">
              <div class="card-header card-header-info card-header-icon">
                  <div class="card-icon">
                      <i class="fa fa-shopping-cart"></i>
                  </div>
                  <p class="card-category">Total Orders</p>
                  <h3 class="card-title"><?php $query = "SELECT order_id FROM orders_info";
                                            $result = mysqli_query($con, $query);
                                            if ($result) {
                                                // it return number of rows in the table. 
                                                $row = mysqli_num_rows($result);

                                                printf(" " . $row);

                                                // close the result. 
                                            }  ?></h3>
              </div>

          </div>
      </div>
      <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
          <div class="card card-stats">
              <div class="card-header card-header-warning card-header-icon">
                  <div class="card-icon">
                      <i class="fa fa-money"></i>
                  </div>
                  <p class="card-category">Total Money</p>
                  <h3 class="card-title"><?php $query = "SELECT SUM(total_amt) AS ttMoney FROM orders_info";
                                            $result = mysqli_query($con, $query);
                                            if ($result) {
                                                // it return number of rows in the table. 
                                                // $row = mysqli_num_rows($result);
                                                $row = mysqli_fetch_array($result);
                                                printf("  $" . $row['ttMoney']);

                                                // close the result. 
                                            }  ?></h3>
              </div>

          </div>
      </div>
  </div>