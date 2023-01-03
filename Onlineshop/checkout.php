<?php
include "db.php";

include "header.php";



?>

<style>
	.row-checkout {
		display: -ms-flexbox;
		/* IE10 */
		display: flex;
		-ms-flex-wrap: wrap;
		/* IE10 */
		flex-wrap: wrap;
		margin: 0 -16px;
	}

	.col-25 {
		-ms-flex: 25%;
		/* IE10 */
		flex: 25%;
	}

	.col-50 {
		-ms-flex: 50%;
		/* IE10 */
		flex: 50%;
	}

	.col-75 {
		-ms-flex: 75%;
		/* IE10 */
		flex: 75%;
	}

	.col-25,
	.col-50,
	.col-75 {
		padding: 0 16px;
	}

	.container-checkout {
		background-color: #f2f2f2;
		padding: 5px 20px 15px 20px;
		border: 1px solid lightgrey;
		border-radius: 3px;
	}

	input[type=text] {
		width: 100%;
		margin-bottom: 20px;
		padding: 12px;
		border: 1px solid #ccc;
		border-radius: 3px;
	}

	label {
		margin-bottom: 10px;
		display: block;
	}

	.icon-container {
		margin-bottom: 20px;
		padding: 7px 0;
		font-size: 24px;
	}

	.checkout-btn {
		background-color: #4CAF50;
		color: white;
		padding: 12px;
		margin: 10px 0;
		border: none;
		width: 100%;
		border-radius: 3px;
		cursor: pointer;
		font-size: 17px;
	}

	.checkout-btn:hover {
		background-color: #45a049;
	}



	hr {
		border: 1px solid lightgrey;
	}

	span.price {
		float: right;
		color: grey;
	}

	/* Responsive layout - when the screen is less than 800px wide, make the two columns stack on top of each other instead of next to each other (also change the direction - make the "cart" column go on top) */
	@media (max-width: 800px) {
		.row-checkout {
			flex-direction: column-reverse;
		}

		.col-25 {
			margin-bottom: 20px;
		}
	}
</style>


<section class="section">
	<div class="container-fluid">
		<div class="row-checkout">
			<?php
			if (isset($_SESSION["uid"])) {
				$sql = "SELECT * FROM user_info WHERE user_id='$_SESSION[uid]'";
				$query = mysqli_query($con, $sql);
				$row = mysqli_fetch_array($query);

				echo '
			<div class="col-75">
				<div class="container-checkout">
				<form id="checkout_form" action="checkout_process.php" method="POST" class="was-validated">

					<div class="row-checkout">
					
					<div class="col-50">
						<h3>Billing Address</h3>
						<label for="fname"><i class="fa fa-user" ></i> Full Name</label>
						<input type="text" id="fname" class="form-control" name="firstname"  value="' . $row["first_name"] . ' ' . $row["last_name"] . '">
						<label for="email"><i class="fa fa-envelope"></i> Email</label>
						<input type="text" id="email" name="email" class="form-control" pattern="^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9]+(\.[a-z]{2,4})$" value="' . $row["email"] . '" required>
						<label for="adr"><i class="fa fa-address-card-o"></i> City</label>
						<input type="text" id="adr" name="address" class="form-control" value="' . $row["address1"] . '" required>
						<label for="city"><i class="fa fa-institution"></i> District</label>
						<input type="text" id="city" name="city" class="form-control" value="' . $row["address2"] . '"  required>

						<div class="row">
						<div class="col-50">
							<label for="state">Address</label>
							<input type="text" id="state" name="state" class="form-control"  required>
						</div>
						<div class="col-50">
							<label for="zip">Zip code</label>
							<input type="text" id="zip" name="zip" class="form-control" pattern="[0-9]{6}" required>
						</div>
						</div>
					</div>
					
					
					<div class="col-50">
						<h3>Payment</h3>
						<label for="fname">Accepted Cards</label>
						<div class="icon-container">
						<i class="fa fa-cc-visa" style="color:navy;"></i>
						<i class="fa fa-cc-amex" style="color:blue;"></i>
						<i class="fa fa-cc-mastercard" style="color:red;"></i>
						<i class="fa fa-cc-discover" style="color:orange;"></i>
						</div>
						
						
						<label for="cname">Name on Card</label>
						<input type="text" id="cname" name="cardname" class="form-control" pattern="^[a-zA-Z ]+$" required>
						
						<div class="form-group" id="card-number-field">
                        <label for="cardNumber">Card Number</label>
                        <input type="text" class="form-control" id="cardNumber" pattern="[0-9]{12}$" name="cardNumber" required>
                    </div>
						<label for="expdate">Exp Date</label>
						<input type="text" id="expdate" name="expdate" class="form-control" pattern="^((0[1-9])|(1[0-2]))\/(\d{2})$" placeholder="12/23"required>
						

						<div class="row">
						
						<div class="col-50">
							<div class="form-group CVV">
								<label for="cvv">CVV</label>
								<input type="text" class="form-control" name="cvv" id="cvv" pattern="[0-9]{6}$" required>
						</div>
						</div>
					</div>
					</div>
					</div>
					<label><input type="CHECKBOX" name="q" class="roomselect" value="conform" required> Shipping address same as billing
					</label>';
				$i = 1;
				$total = 0;
				$total_count = $_POST['total_count'];

				$sql = "SELECT product_title, product_price*qty as amount, qty, product_id FROM `cart` join products on cart.p_id = products.product_id where cart.user_id = '$_SESSION[uid]';";
				$query = mysqli_query($con, $sql);
				while ($row = mysqli_fetch_array($query)) {
					$item_name_ = $row['product_title'];
					$amount_ = $row['amount'];
					$quantity_ = $row['qty'];
					$total = $total + $amount_;
					$product_id = $row["product_id"];
					echo "	
						<input type='hidden' name='prod_id_$i' value='$product_id'>
						<input type='hidden' name='prod_price_$i' value='$amount_'>
						<input type='hidden' name='prod_qty_$i' value='$quantity_'>
						";
					$i++;
				}

				echo '	
				<input type="hidden" name="total_count" value="' . $total_count . '">
					<input type="hidden" name="total_price" value="' . $total . '">
					
					<input type="submit" id="submit" value="Continue to checkout" class="checkout-btn">
				</form>
				</div>
			</div>
			';
			} else {
				echo "<script>window.location.href = 'cart.php'</script>";
			}
			?>

			<div class="col-25">
				<div class="container-checkout">

					<?php
					if (isset($_POST["cmd"])) {

						$user_id = $_POST['custom'];


						$i = 1;
						echo
						"
					<h4>Cart 
					<span class='price' style='color:black'>
					<i class='fa fa-shopping-cart'></i> 
					<b>$total_count</b>
					</span>
				</h4>

					<table class='table table-condensed'>
					<thead><tr>
					<th >no</th>
					<th >product title</th>
					<th >	qty	</th>
					<th >	amount</th></tr>
					</thead>
					<tbody>
					";
						$total = 0;
						$sql = "SELECT product_title, product_price*qty as amount, qty, product_id FROM `cart` join products on cart.p_id = products.product_id where cart.user_id = '$_SESSION[uid]';";
						$query = mysqli_query($con, $sql);
						$ss = 0;
						while ($row = mysqli_fetch_array($query)) {
							$item_name_ = $row['product_title'];

							$item_number_ = ++$ss;

							$amount_ = $row['amount'];

							$quantity_ = $row['qty'];
							$total = $total + $amount_;
							$product_id = $row["product_id"];

							echo "	

						<tr><td><p>$item_number_</p></td><td><p>$item_name_</p></td><td ><p>$quantity_</p></td><td ><p>$amount_</p></td></tr>";

							$i++;
						}

						echo "

				</tbody>
				</table>
				<hr>
				
				<h3>total<span class='price' style='color:black'><b>$$total</b></span></h3>";
					}
					?>
				</div>
			</div>
		</div>
	</div>
</section>
<div id="newsletter" class="section">
	<!-- container -->
	<div class="container">
		<!-- row -->
		<div class="row">
			<div class="col-md-12">
				<div class="newsletter">
					<p>Sign Up for the <strong>NEWSLETTER</strong></p>
					<form>
						<input class="input" type="email" placeholder="Enter Your Email">
						<button class="newsletter-btn"><i class="fa fa-envelope"></i> Subscribe</button>
					</form>
					<ul class="newsletter-follow">
						<li>
							<a href="#"><i class="fa fa-facebook"></i></a>
						</li>
						<li>
							<a href="#"><i class="fa fa-twitter"></i></a>
						</li>
						<li>
							<a href="#"><i class="fa fa-instagram"></i></a>
						</li>
						<li>
							<a href="#"><i class="fa fa-pinterest"></i></a>
						</li>
					</ul>
				</div>
			</div>
		</div>
		<!-- /row -->
	</div>
	<!-- /container -->
</div>