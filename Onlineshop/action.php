<?php
try {
	@session_start();
	$ip_add = getenv("REMOTE_ADDR");
	include "db.php";
	function Check($Input, $check)
	{
		$Input = " " . $Input;
		return strpos($Input, $check) or strpos(strtoupper($Input), strtoupper($check));
	}
	function MultiCheck($Input)
	{
		return (Check($Input, "javascript:")
			or Check($Input, "<script")
			or Check($Input, "<php")
			or Check($Input, "<?")
			or Check($Input, "<form"));
	}
	function Validate($Input)
	{
		$Input = str_replace('<', '&#60', $Input);
		$Input = str_replace('>', '&#62', $Input);
		$Input = str_replace('\'', '\\\'', $Input);
		$Input = str_replace('javascript', htmlentities("javascript"), $Input);
		$Input = str_replace('js', htmlentities("js"), $Input);
		return $Input;
	}
	if (isset($_POST["category"])) {

		$category_query = "SELECT * FROM categories";

		$run_query = mysqli_query($con, $category_query) or die(mysqli_error($con));
		echo "
		
            
            <div class='aside'>
							<h3 class='aside-title'>Categories</h3>
							<div class='btn-group-vertical'>
	";
		if (mysqli_num_rows($run_query) > 0) {
			$i = 1;
			while ($row = mysqli_fetch_array($run_query)) {

				$cid = $row["cat_id"];
				$cat_name = $row["cat_title"];
				$sql = "SELECT COUNT(*) AS count_items FROM products WHERE product_cat=$cid";
				$query = mysqli_query($con, $sql);
				$row = mysqli_fetch_array($query);
				$count = $row["count_items"];
				$i++;


				echo "
					
                    <div type='button' class='btn navbar-btn category' cid='$cid'>
									
									<a href='#'>
										<span  ></span>
										$cat_name
										<small class='qty'>($count)</small>
									</a>
								</div>
                    
			";
			}


			echo "</div>";
		}
	}
	if (isset($_POST["brand"])) {
		$brand_query = "SELECT * FROM brands";
		$run_query = mysqli_query($con, $brand_query);
		echo "
		<div class='aside'>
							<h3 class='aside-title'>Brand</h3>
							<div class='btn-group-vertical'>
	";
		if (mysqli_num_rows($run_query) > 0) {
			$i = 1;
			while ($row = mysqli_fetch_array($run_query)) {

				$bid = $row["brand_id"];
				$brand_name = $row["brand_title"];
				$sql = "SELECT COUNT(*) AS count_items FROM products WHERE product_brand=$bid";
				$query = mysqli_query($con, $sql);
				$row = mysqli_fetch_array($query);
				$count = $row["count_items"];
				$i++;
				echo "
					
                    
                    <div type='button' class='btn navbar-btn selectBrand' bid='$bid'>
									
									<a href='#'>
										<span ></span>
										$brand_name
										<small >($count)</small>
									</a>
								</div>
			";
			}
			echo "</div>";
		}
	}
	if (isset($_POST["page"])) {
		if (isset($_POST['col']) and isset($_POST['value'])) {
			$col = mysqli_real_escape_string($con, $_POST['col']);
			if ($_POST['col'] != "product_title" and is_numeric($_POST['value']))
				$value = (int) $_POST['value'];
			else
				$value = $_POST['value'];
		} else {
			$col = "product_cat";
			$value = 1;
		}
		if ($col == "product_title") {
			$keyword = mysqli_real_escape_string($con, $value);
			$sql = "SELECT COUNT(*) AS count FROM products inner join `categories` on products.product_cat = categories.cat_id
		 WHERE product_cat=cat_id AND $col LIKE " . "'%$keyword%'";
			$run_query = mysqli_query($con, $sql);
			$row = mysqli_fetch_array($run_query);
			$count = $row['count'];
			$pageno = ceil($count / 9);
			for ($i = 1; $i <= $pageno; $i++) {
				echo "
				<li><a href='#product-row' page='$i' id='page' class='active'>$i</a></li>
			";
			}
		} else {
			$sql = "SELECT * FROM products WHERE $col = '$value'";
			$run_query = mysqli_query($con, $sql);
			$count = mysqli_num_rows($run_query);
			$pageno = ceil($count / 9);
			for ($i = 1; $i <= $pageno; $i++) {
				echo "
				<li><a href='#product-row' page='$i' id='page' class='active'>$i</a></li>
			";
			}
		}

	}
	if (isset($_POST["getProduct"])) {
		$limit = 9;
		if (isset($_POST["setPage"])) {
			$pageno = $_POST["pageNumber"];
			#kiểm tra SQL injection: kiểu số
			if (!is_numeric($pageno))
				$pageno = 1;
			$start = ($pageno * $limit) - $limit;
		} else {
			$start = 0;
		}
		$product_query = "SELECT * FROM products,categories WHERE product_cat=cat_id LIMIT $start,$limit";

		$run_query = mysqli_query($con, $product_query);
		if (mysqli_num_rows($run_query) > 0) {
			while ($row = mysqli_fetch_array($run_query)) {
				$pro_id = $row['product_id'];
				$pro_cat = $row['product_cat'];
				$pro_brand = $row['product_brand'];
				$pro_title = $row['product_title'];
				$pro_price = $row['product_price'];
				$pro_image = $row['product_image'];

				$cat_name = $row["cat_title"];
				echo "
				
                        
                        <div class='col-md-4 col-xs-6' >
								<a href='product.php?p=$pro_id'><div class='product'>
									<div class='product-img'>
										<img src='product_images/$pro_image' style='height: 230px;' alt=''>
									</div></a>
									<div class='product-body'>
										<p class='product-category'>$cat_name</p>
										<h3 class='product-name header-cart-item-name'><a href='product.php?p=$pro_id'>$pro_title</a></h3>
										<h4 class='product-price header-cart-item-info'>$pro_price $</h4>
										
									</div>
									<div class='add-to-cart'>
										<button pid='$pro_id' id='product' class='add-to-cart-btn block2-btn-towishlist' href='#'><i class='fa fa-shopping-cart'></i> add to cart</button>
									</div>
								</div>
							</div>
                        
			";
			}
		}
	}


	if (isset($_POST["get_seleted_Category"]) || isset($_POST["selectBrand"]) || isset($_POST["search"])) {
		if (isset($_POST["get_seleted_Category"])) {
			$id = $_POST["cat_id"];
			#kiểm tra SQL injection: kiểu số
			if (!is_numeric($id)) {
				echo "<script>alert('Trang có thông số nguy hiểm do nghi ngờ có mã độc. Vui lòng không thử lại thao tác.')</script>";
				$id = 1;
			}
			$sql = "SELECT * FROM products,categories WHERE product_cat = '$id' AND product_cat=cat_id";
		} else if (isset($_POST["selectBrand"])) {
			$id = $_POST["brand_id"];
			#kiểm tra SQL injection: kiểu số
			if (!is_numeric($id)) {
				echo "<script>alert('Trang có thông số nguy hiểm do nghi ngờ có mã độc. Vui lòng không thử lại thao tác.')</script>";
				$id = 1;
			}
			$sql = "SELECT * FROM products,categories WHERE product_brand = '$id' AND product_cat=cat_id";
		} else {
			$keyword = $_POST["keyword"];
			$keyword = mysqli_real_escape_string($con, $keyword);
			$sql = "SELECT * FROM products inner join `categories` on products.product_cat = categories.cat_id
		 WHERE product_cat=cat_id AND product_title LIKE " . "'%$keyword%'";
			#chống XSS: mã hoá dữ liệu đầu vào sang mã ASCII.
			echo "<div>Kết quả tìm kiếm cho từ khoá <b id='keyword'>" . htmlentities($keyword) . "</b>: " . mysqli_num_rows(mysqli_query($con, $sql)) . " sản phẩm.</div>";
		}

		$sort = $_POST["get_sort"];
		if ($sort == "Name")
			$sort = "product_title";
		else if ($sort == "Money")
			$sort = "product_price";
		else
			$sort = "product_title";

		$amount = 9;

		#kiểm tra sqli kiểu số:
		if (is_numeric($_POST["pageNumber"]))
			$start = ((int) $_POST["pageNumber"] - 1) * $amount;
		else
			$start = 1;
		$sql = $sql . " ORDER BY $sort ASC LIMIT $start, $amount";
		#kiểm tra SQL injection: kiểu số
		if (!is_numeric($amount))
			$amount = 20;

		$index = 0;
		$run_query = mysqli_query($con, $sql);
		while ($row = mysqli_fetch_array($run_query)) {
			$index++;
			$pro_id = $row['product_id'];
			$pro_cat = $row['product_cat'];
			$pro_brand = $row['product_brand'];
			$pro_title = $row['product_title'];
			$pro_price = $row['product_price'];
			$pro_image = $row['product_image'];
			$cat_name = $row["cat_title"];
			echo "      
				<div class='col-md-4 col-xs-6'>
						<a href='product.php?p=$pro_id'><div class='product'>
							<div class='product-img'>
								<img  src='product_images/$pro_image'  style='height: 230px;' alt=''>
							</div>
						</a>
						<div class='product-body'>
							<p class='product-category'>$cat_name</p>
							<h3 class='product-name header-cart-item-name'><a href='product.php?p=$pro_id'>$pro_title</a></h3>
							<h4 class='product-price header-cart-item-info'>$pro_price $</h4>
							
						</div>
						<div class='add-to-cart'>
							<button pid='$pro_id' id='product' href='#' tabindex='0' class='add-to-cart-btn'><i class='fa fa-shopping-cart'></i> add to cart</button>
						</div>
					</div>
				</div>
			";
		}
		echo "<div style='display:none' class='cuong'>$index</div>";
	}



	if (isset($_POST["addToCart"])) {


		$p_id = $_POST["proId"];
		#kiểm tra SQL injection: kiểu số
		if (!is_numeric($p_id)) {
			echo "<script>alert('Trang có thông số nguy hiểm do nghi ngờ có mã độc. Vui lòng không thử lại thao tác.')</script>";
		} else if (isset($_SESSION["uid"])) {

			$user_id = $_SESSION["uid"];

			$sql = "SELECT * FROM cart WHERE p_id = '$p_id' AND user_id = '$user_id'";
			$run_query = mysqli_query($con, $sql);
			$count = mysqli_num_rows($run_query);
			if ($count > 0) {
				echo "
				<div class='alert alert-warning'>
						<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
						<b>Product is already added into the cart Continue Shopping..!</b>
				</div>
			"; //not in video
			} else {
				$sql = "INSERT INTO `cart`
			(`p_id`, `ip_add`, `user_id`, `qty`) 
			VALUES ('$p_id','$ip_add','$user_id','1')";
				if (mysqli_query($con, $sql)) {
					echo "
					<div class='alert alert-success'>
						<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
						<b>Product is Added..!</b>
					</div>
				";
				}
			}
		} else {
			$sql = "SELECT id FROM cart WHERE ip_add = '$ip_add' AND p_id = '$p_id' AND user_id = -1";
			$query = mysqli_query($con, $sql);
			if (mysqli_num_rows($query) > 0) {
				echo "
					<div class='alert alert-warning'>
							<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
							<b>Product is already added into the cart Continue Shopping..!</b>
					</div>";
				exit();
			}
			$sql = "INSERT INTO `cart`
			(`p_id`, `ip_add`, `user_id`, `qty`) 
			VALUES ('$p_id','$ip_add','-1','1')";
			if (mysqli_query($con, $sql)) {
				echo "
					<div class='alert alert-success'>
						<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
						<b>Your product is Added Successfully..!</b>
					</div>
				";
				exit();
			}
		}
	}

	//Count User cart item
	if (isset($_POST["count_item"])) {
		//When user is logged in then we will count number of item in cart by using user session id
		if (isset($_SESSION["uid"])) {
			$sql = "SELECT COUNT(*) AS count_item FROM cart WHERE user_id = $_SESSION[uid]";
		} else {
			//When user is not logged in then we will count number of item in cart by using users unique ip address
			$sql = "SELECT COUNT(*) AS count_item FROM cart WHERE ip_add = '$ip_add' AND user_id < 0";
		}

		$query = mysqli_query($con, $sql);
		$row = mysqli_fetch_array($query);
		echo $row["count_item"];
		exit();
	}
	//Count User cart item

	//Get Cart Item From Database to Dropdown menu
	if (isset($_POST["Common"])) {

		if (isset($_SESSION["uid"])) {
			//When user is logged in this query will execute
			$sql = "SELECT a.product_id,a.product_desc,a.product_title,a.product_price,a.product_image,b.id,b.qty FROM products a,cart b WHERE a.product_id=b.p_id AND b.user_id='$_SESSION[uid]'";
		} else {
			//When user is not logged in this query will execute
			$sql = "SELECT a.product_id,a.product_desc,a.product_title,a.product_price,a.product_image,b.id,b.qty FROM products a,cart b WHERE a.product_id=b.p_id AND b.ip_add='$ip_add' AND b.user_id < 0";
		}
		$query = mysqli_query($con, $sql);
		if (isset($_POST["getCartItem"])) {
			//display cart item in dropdown menu
			if (mysqli_num_rows($query) > 0) {
				$n = 0;
				$total_price = 0;
				while ($row = mysqli_fetch_array($query)) {

					$n++;
					$product_id = $row["product_id"];
					$product_title = $row["product_title"];
					$product_price = $row["product_price"];
					$product_image = $row["product_image"];
					$cart_item_id = $row["id"];
					$qty = $row["qty"];
					$total_price = $total_price + $product_price;
					echo '
					
                    
                    <div class="product-widget">
												<div class="product-img">
													<img src="product_images/' . $product_image . '" alt="">
												</div>
												<div class="product-body">
													<h3 class="product-name"><a href="#">' . $product_title . '</a></h3>
													<h4 class="product-price"><span class="qty">' . $n . '</span>$' . $product_price . '</h4>
												</div>
												
											</div>';
				}

				echo '<div class="cart-summary">
				    <small class="qty">' . $n . ' Item(s) selected</small>
				    <h5>$' . $total_price . '</h5>
				</div>'
					?>
				
				
			<?php

					exit();
			}
		}


		if (isset($_POST["checkOutDetails"])) {
			if (mysqli_num_rows($query) > 0) {
				//display user cart item with "Ready to checkout" button if user is not login
				echo '<div class="mainaction.php ">
			<div class="table-responsive">
			<form method="post" action="login_form.php">
			
	               <table id="cart" class="table table-hover table-condensed" id="">
    				<thead>
						<tr>
							<th style="width:50%">Product</th>
							<th style="width:10%">Price</th>
							<th style="width:8%">Quantity</th>
							<th style="width:7%" class="text-center">Subtotal</th>
							<th style="width:10%"></th>
						</tr>
					</thead>
					<tbody>
                    ';
				$n = 0;
				while ($row = mysqli_fetch_array($query)) {
					$n++;
					$product_id = $row["product_id"];
					$product_title = $row["product_title"];
					$product_price = $row["product_price"];
					$product_image = $row["product_image"];
					$product_desc = $row["product_desc"];
					$cart_item_id = $row["id"];
					$qty = $row["qty"];

					echo
						'
                             
						<tr>
							<td data-th="Product" >
								<div class="row">
								
									<div class="col-sm-4 "><img src="product_images/' . $product_image . '" style="height: 70px;width:75px;"/>
									<h4 class="nomargin product-name header-cart-item-name"><a href="product.php?p=' . $product_id . '">' . $product_title . '</a></h4>
									</div>
									<div class="col-sm-6">
										<div style="max-width=50px;">
										<p>' . $product_desc . '</p>
										</div>
									</div>
									
									
								</div>
							</td>
                            <input type="hidden" name="product_id[]" value="' . $product_id . '"/>
				            <input type="hidden" name="" value="' . $cart_item_id . '"/>
							<td data-th="Price"><input type="text" class="form-control price" value="' . $product_price . '" readonly="readonly"></td>
							<td data-th="Quantity">
								<input type="number" onchange="posNumber(this)" class="form-control qty" value="' . $qty . '" >
							</td>
							<td data-th="Subtotal" class="text-center"><input type="text" class="form-control total" value="' . $product_price . '" readonly="readonly"></td>
							<td class="actions" data-th="">
							<div class="btn-group">
								<a href="#" class="btn btn-info btn-sm update" update_id="' . $product_id . '">
									<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="bi bi-file-text" viewBox="0 0 16 16">
									<path d="M5 4a.5.5 0 0 0 0 1h6a.5.5 0 0 0 0-1H5zm-.5 2.5A.5.5 0 0 1 5 6h6a.5.5 0 0 1 0 1H5a.5.5 0 0 1-.5-.5zM5 8a.5.5 0 0 0 0 1h6a.5.5 0 0 0 0-1H5zm0 2a.5.5 0 0 0 0 1h3a.5.5 0 0 0 0-1H5z"/>
									<path d="M2 2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2zm10-1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1z"/>
									</svg>
								</a>
								<a href="#" class="btn btn-danger btn-sm remove" remove_id="' . $product_id . '"><i class="fa fa-trash-o"></i></a>		
							</div>							
							</td>
						</tr>
					
                            
                            ';
				}

				echo '</tbody>
				<tfoot>
					<tr>
						<td style="border: none" colspan="3">
						</td>
						<td style="border: none" class="hidden-xs text-center"><b class="net_total" ></b></td>
						<td style="border: none">
					<a href="#" class="btn btn-info btn-sm allupdate">
					<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="bi bi-file-text" viewBox="0 0 16 16">
					<path d="M5 4a.5.5 0 0 0 0 1h6a.5.5 0 0 0 0-1H5zm-.5 2.5A.5.5 0 0 1 5 6h6a.5.5 0 0 1 0 1H5a.5.5 0 0 1-.5-.5zM5 8a.5.5 0 0 0 0 1h6a.5.5 0 0 0 0-1H5zm0 2a.5.5 0 0 0 0 1h3a.5.5 0 0 0 0-1H5z"/>
					<path d="M2 2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2zm10-1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1z"/>
					</svg>
					</a>
						</td>
					</tr>
					<tr>
						<td><a href="store.php" class="btn btn-warning"><i class="fa fa-angle-left"></i> Continue Shopping</a></td>
						<td colspan="3" class="hidden-xs"></td>
						
						<div id="issessionset"></div>
                        <td>
							
							';
				if (!isset($_SESSION["uid"])) {
					echo '
					
							<a href="" data-toggle="modal" data-target="#Modal_login" class="btn btn-success">Ready to Checkout</a></td>
								</tr>
							</tfoot>
				
							</table></div></div>';
				} else if (isset($_SESSION["uid"])) {
					//Paypal checkout form
					echo '
					</form>
					
						<form action="checkout.php" method="post" class="allupdate">
							<input type="hidden" name="cmd" value="_cart">
							<input type="hidden" name="business" value="shoppingcart@puneeth.com">
							<input type="hidden" name="upload" value="1">';

					$x = 0;
					$sql = "SELECT a.product_id,a.product_title,a.product_price,a.product_image,b.id,b.qty FROM products a,cart b WHERE a.product_id=b.p_id AND b.user_id='$_SESSION[uid]'";
					$query = mysqli_query($con, $sql);
					echo '<input type="hidden" name="total_count" value="' . mysqli_num_rows($query) . '">';
					while ($row = mysqli_fetch_array($query)) {
						$x++;
						echo

							'<input type="hidden" name="item_id_' . $x . '" value="' . $row["product_id"] . '">';
					}

					echo
						'<input type="hidden" name="return" value="http://localhost/myfiles/public_html/payment_success.php"/>
					                <input type="hidden" name="notify_url" value="http://localhost/myfiles/public_html/payment_success.php">
									<input type="hidden" name="cancel_return" value="http://localhost/myfiles/public_html/cancel.php"/>
									<input type="hidden" name="currency_code" value="USD"/>
									<input type="hidden" name="custom" value="' . $_SESSION["uid"] . '"/>
									<input type="submit" id="submit" name="login_user_with_product" name="submit" class="btn btn-success" value="Ready to Checkout">
									</form></td>
									
									</tr>
									
									</tfoot>
									
							</table></div></div>    
								';
				}
			} else {
				echo "<div class='mainaction.php '>
				<div class='table-responsive'>
				<div style='text-align:center;font-size:20px;color:#555555;margin-bottom:5%;margin-top:6%'>
				THERE IS NO ANY PRODUCT ADDED TO CART.
				</div>
				</div>
				</div>";
			}
		}
	}

	//Remove Item From cart
	if (isset($_POST["removeItemFromCart"])) {
		$remove_id = $_POST["rid"];
		#kiểm tra SQL injection: kiểu số
		if (!is_numeric($remove_id)) {
			echo "<script>alert('Trang có thông số nguy hiểm do nghi ngờ có mã độc. Vui lòng không thử lại thao tác.')</script>";
		} else {
			if (isset($_SESSION["uid"])) {
				$sql = "DELETE FROM cart WHERE p_id = '$remove_id' AND user_id = '$_SESSION[uid]'";
			} else {
				$sql = "DELETE FROM cart WHERE p_id = '$remove_id' AND ip_add = '$ip_add'";
			}
			if (mysqli_query($con, $sql)) {
				echo "<div class='alert alert-danger'>
						<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
						<b>Product is removed from cart</b>
				</div>";
				exit();
			}
		}
	}


	//Update Item From cart
	if (isset($_POST["updateCartItem"])) {
		$update_id = $_POST["update_id"];
		$qty = $_POST["qty"];
		#kiểm tra SQL injection: kiểu số
		if (!is_numeric($update_id) || !is_numeric($qty)) {
			echo "<script>alert('Trang có thông số nguy hiểm do nghi ngờ có mã độc. Vui lòng không thử lại thao tác.')</script>";
		} else {
			if (isset($_SESSION["uid"])) {
				$sql = "UPDATE cart SET qty='$qty' WHERE p_id = '$update_id' AND user_id = '$_SESSION[uid]'";
			} else {
				$sql = "UPDATE cart SET qty='$qty' WHERE p_id = '$update_id' AND ip_add = '$ip_add'";
			}
			if (mysqli_query($con, $sql)) {
				echo "<div class='alert alert-info'>
							<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
							<b>Product is updated</b>
					</div>";
				exit();
			}
		}
	}

	if (isset($_POST["getProfile"])) {
		$index = $_POST["getProfile"];
		if ($index == 1) {

			$rs = mysqli_query($con, "SELECT * FROM user_info WHERE user_id = '$_SESSION[uid]'");
			$row = mysqli_fetch_array($rs);
			echo "<div style='float: right;'>
		<button id='edit-info' style='background-color: lightblue; border: none; padding: 5px 20px;'>Edit infomation</button>
		<button id='change-pwd' style='background-color: lightsalmon; border: none; padding: 5px 20px;'>Change password</button>
		<img src='./img/pen.png' height='20px'>
		</div>
		<div class='title' style='margin: 20px 10px'>Home</div>
		<div>
			<div style='font-size: 18px; margin-left: 10px;'>Infomation: </div> 
		</div>
		<div class='account-info'>
			<div class='mono-info'>
				<b>Email: </b>
				<span>$row[email]</span>
			</div>
		</div>
		
		<div class='user-info'>
			<div class='mono-info'>
				<b>First-name: </b>
				<span>$row[first_name]</span>
			</div>
			<div class='mono-info'>
				<b>Last-name: </b>
				<span>$row[last_name]</span>
			</div>
			<div class='mono-info'>
				<b>Mobie: </b>
				<span>$row[mobile]</span>
			</div>
			<div class='mono-info'>
				<b>Address: </b>
				<span>$row[address1]</span>
			</div>
			<div class='mono-info'>
				<b>City: </b>
				<span>$row[address2]</span>
			</div>
		</div>";
		} else if ($index == 3) {
			$sql = "SELECT * FROM `order_products` 
        INNER JOIN orders_info on order_products.order_id = orders_info.order_id 
        INNER JOIN products on products.product_id = order_products.product_id 
        where user_id = '$_SESSION[uid]'";
			$rs = mysqli_query($con, $sql);
			$count = mysqli_num_rows($rs);
			echo "<div class='title' style='margin: 20px 10px'>Order</div>
        <div>
            <div style='font-size: 18px; margin-left: 10px;'>Details: $count Orders</div>
        </div>";

			while ($row = mysqli_fetch_array($rs)) {
				echo "
        <div class='row'
            style='margin-top: 30px; padding: 10px; border-top: 3px solid #a0a0a0; border-bottom;: 3px solid #a0a0a0'>
            <div>
                <div class='col-md-3'>
                    <img src='./product_images/$row[product_image]' height='200px'>
                </div>
                <div class='col-md-9'>
                    <div class='col-md-12'>
                        <table class='table-order'>
                            <tr>
                                <td width='70%'>$row[product_title]</td>
                                <td>$row[product_price] $</td>
                                <td>x $row[qty]</td>
                            </tr>
                        </table>
                    </div>
                    <div class='col-md-12 info-shipping'>
                        <div>Desciption: $row[product_desc]</div>
                        <div>State: $row[state]</div>
                        <div>City: $row[city]</div>
                        <div>Address: $row[address]</div>
                    </div>
                </div>
                <div class='col-md-12'
                    style='margin-top: 20px; font-size: 24px; font-weight: 500; text-align: right;'>
                    <span>Total: </span>
                    <b>$row[amt] $</b>
                </div>
            </div>
        </div>";
			}

		} else if ($index == 4) {
			$sql = "SELECT order_id  FROM `orders_info` WHERE user_id = '$_SESSION[uid]'";
			$rs = mysqli_query($con, $sql);
			$count = mysqli_num_rows($rs);
			echo "<div class='title' style='margin: 20px 10px'>Bill</div>
        <div>
            <div style='font-size: 18px; margin-left: 10px;'>Details: $count Bill</div>
        </div>";
			$ss = 0;
			echo "
        <div class='row'
            style='margin-top: 30px; padding: 10px; border-top: 3px solid #a0a0a0; border-bottom;: 3px solid #a0a0a0'>
            <div>
                <table style='width: 100%; text-align: center' border=1; class='table-order'>
					<caption>List of bills</caption>";
			while ($row = mysqli_fetch_array($rs)) {
				$ss++;
				echo "
					<tr>
						<td>
							$ss
						</td>
						<td>
							<a href='bill_view.php?file=order$row[order_id].pdf'> BILL </a>
						</td>
					</tr>";
			}
			echo "
				</table>
			</div>
		</div>";

		}

	}
	if (isset($_POST["editInfo"])) {
		$rs = mysqli_query($con, "SELECT * FROM user_info WHERE user_id = '$_SESSION[uid]'");
		$row = mysqli_fetch_array($rs);
		echo "
    <div class='title' style='margin: 20px 10px'>Home</div>
    <div>
        <div style='font-size: 18px; margin-left: 10px;'>Infomation: </div> 
    </div>
    <div class='col-md-12'>
    <form action='action.php' method='post'>
    <table class='table-info'>
        <tr class='mono-info'>
            <td><b>First-name </b></td>
            <td>:</td>
            <td> <input name='first_name' value='$row[first_name]'></td>
        </tr>
        <tr class='mono-info'>
            <td><b>Last-name </b></td>
            <td>:</td>
            <td><input name='last_name' value='$row[last_name]'></td>
        </tr>
        <tr class='mono-info'>
            <td><b>Mobie </b></td>
            <td>:</td>
            <td><input name='mobile' value='$row[mobile]'></td>
        </tr>
        <tr class='mono-info'>
            <td><b>Address </b></td>
            <td>:</td>
            <td><input name='address1' value='$row[address1]'></td>
        </tr>
        <tr class='mono-info'>
            <td><b>City </b></td>
            <td>:</td>
            <td><input name='address2' value='$row[address2]'></td>
        </tr>
    </table>
    <div style='text-align: right'>
        <input class='submit-button' name='changeInfo' type='submit' value='Change'>
    </div>
    </form>
    </div>";
	}
	if (isset($_POST["changeInfo"])) {
		$first_name = mysqli_real_escape_string($con,$_POST["first_name"]);
		$last_name = mysqli_real_escape_string($con,$_POST["last_name"]);
		$mobile = mysqli_real_escape_string($con,$_POST["mobile"]);
		$address1 = mysqli_real_escape_string($con,$_POST["address1"]);
		$address2 = mysqli_real_escape_string($con,$_POST["address2"]);
		if(MultiCheck($first_name) or MultiCheck($last_name) or MultiCheck($mobile) or MultiCheck($address1) or MultiCheck($address2)) {
			echo "<script>
        	alert('Some field are suspected of having malicious code');
        	</script>";
			include("./profile.php");
        	exit();
		}
		$sql = "UPDATE user_info SET first_name = '$first_name',
    last_name = '$last_name',
    mobile = '$mobile',
    address1 = '$address1',
    address2 = '$address2'  WHERE user_id = '$_SESSION[uid]' ;";
		mysqli_query($con, $sql);
		header("location: ./profile.php");
	}
	if (isset($_POST["changePwd"])) {
		$rs = mysqli_query($con, "SELECT * FROM user_info WHERE user_id = '$_SESSION[uid]'");
		$row = mysqli_fetch_array($rs);
		echo "
    <div class='title' style='margin: 20px 10px'>Home</div>
    <div>
        <div style='font-size: 18px; margin-left: 10px;'>Change password:</div> 
    </div>
    <div class='col-md-12'>
    <form action='action.php' method='post'>
    <table class='table-info'>
        <tr class='mono-info'>
            <td><b>Old passwork </b></td>
            <td>:</td>
            <td> <input type='password' name='Oldpwd' value=''></td>
        </tr>
        <tr class='mono-info'>
            <td><b>New password </b></td>
            <td>:</td>
            <td><input type='password' name='Newpwd' value=''></td>
        </tr>
        <tr class='mono-info'>
            <td><b>Confirm password </b></td>
            <td>:</td>
            <td><input type='password' name='Cfpwd' value=''></td>
        </tr>
    </table>
    <div style='text-align: right'>
        <input class='submit-button' name='changepwd' type='submit' value='Change'>
    </div>
    </form>
    </div>";
	}
	if (isset($_POST["changepwd"])) {
		$oldpass = mysqli_real_escape_string($con,$_POST['Oldpwd']);
		$rs = mysqli_query($con, "SELECT * FROM user_info WHERE user_id = '$_SESSION[uid]' and password='$oldpass' or password='" . md5($oldpass) . "'");
		$row = mysqli_fetch_array($rs);
		if (mysqli_num_rows($rs) > 0 and ($_POST["Newpwd"] == $_POST["Cfpwd"])) {
			$newpass = mysqli_real_escape_string($con,$_POST['Newpwd']);
			$sql = "UPDATE user_info SET password = '".md5($newpass)."' WHERE user_id = '$_SESSION[uid]' ;";
			mysqli_query($con, $sql);
			header("location: ./profile.php");
		} else {
			if ($_POST["Newpwd"] != $_POST["Cfpwd"])
			{
					// $error = "Mật khẩu nhập lại không khớp.";
					header("location: ./profile.php");
			}

			else if (mysqli_num_rows($rs) == 0) {
				// $error = "Sai mật khẩu.";
				header("location: ./profile.php");
			}
			echo "
    <div class='title' style='margin: 20px 10px'>Home</div>
    <div>
        <div style='font-size: 18px; margin-left: 10px;'>Change password:</div> 
    </div>
    <div class='col-md-12'>
    <form action='action.php' method='post'>
    <table class='table-info'>
        <tr class='mono-info'>
            <td><b>Old passwork </b></td>
            <td>:</td>
            <td> <input type='password' name='Oldpwd' value=''></td>
        </tr>
        <tr class='mono-info'>
            <td><b>New password </b></td>
            <td>:</td>
            <td><input type='password' name='Newpwd' value=''></td>
        </tr>
        <tr class='mono-info'>
            <td><b>Confirm password </b></td>
            <td>:</td>
            <td><input type='password' name='Cfpwd' value=''></td>
        </tr>
    </table>
    <div style='text-align: right'>
        <input class='submit-button' name='changepwd' type='submit' value='Change'>
    </div>
    </form>
    </div>
    <div>
        <div style='color: red; padding: 20px; text-align: center;'>
            $error !;
        </div>
    </div>";
		}
	}
	if (isset($_GET["comment"])) {
		if (isset($_SESSION['uid']) && trim($_GET["comment"], " ") != "") {
			$_GET["comment"] = Validate($_GET["comment"]);

			if (MultiCheck($_GET["comment"]))
				echo "<script>alert('Thao tác không hợp lệ. Bạn vừa thực hiện một hành động đáng ngờ.')</script>";
			else {
				$sql = "INSERT INTO `comment` (`User_id`, `Product_id`, `Content`) VALUES ('$_SESSION[uid]','$_GET[p]','$_GET[comment]');";
				$rs = mysqli_query($con, $sql);
			}
			$sql = "SELECT * FROM `comment` INNER JOIN user_info on comment.User_id = user_info.user_id WHERE comment.Product_id = '$_GET[p]'";
			$rs = mysqli_query($con, $sql);
			while ($row = mysqli_fetch_array($rs)) {
				echo '
            <li>
				<div class="review-heading">
					<h5 class="name">' . $row["first_name"] . ' ' . $row["last_name"] . '</h5>
				</div>
				<div class="review-body">
					<p>' . $row["Content"] . '</p>
				</div>
			</li>';
			}
		}
	}
}
catch(Exception $e) {
	echo "<script>alert('There are exceptions. Please try again.')</script>";
	include("./index.php");
}

			?>
