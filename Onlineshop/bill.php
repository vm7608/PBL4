<?php
include("db.php");
if (isset($_GET['file'])) {
	$orderid = substr($_GET['file'], 5, -4);
} else if (isset($orid)) {
	$orderid = $orid;
} else
	header("location: index.php");
?>
<link href="./css/bill.css" rel="stylesheet">
<div class="container">
	<div class="row gutters">
		<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
			<div class="card">
				<div class="card-body p-0">
					<div class="invoice-container">
						<div class="invoice-header">
							<!-- Row start -->
							<div class="row gutters">
								<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">

									<div class="custom-actions-btns mb-5">

										<a href="#" class="btn btn-primary" target="_blank">
											<?php if (!isset($orid))
												echo '<i class="icon-download"></i> Download'
											?>
										</a>
									</div>
								</div>
							</div>
							<!-- Row end -->
							<!-- Row start -->
							<img src="./img/ITF.jpg" height="100px">
							<div style="display: inline-block; margin-left: 20px;" class="row gutters">
								<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">
									<a href="index.html" class="invoice-logo" style="font-size: 50px">
										Safe shop
									</a>
								</div>
								<div class="col-lg-6 col-md-6 col-sm-6">
									<address class="text-right">
										PBL4<br>
										The University of Danang - University of Science and Technology<br>
									</address>
								</div>
							</div>
							<!-- Row end -->
							<!-- Row start -->
							<div class="row gutters">
								<div class="col-xl-9 col-lg-9 col-md-12 col-sm-12 col-12">
									<div class="invoice-details">
										<address>
											<?php
											$sql = "SELECT  f_name, email, address, city,state  FROM `orders_info` 
												WHERE orders_info.order_id = '$orderid'";
											$rs = mysqli_query($con, $sql);
											$row = mysqli_fetch_array($rs);
											echo "
												<table style='width: 100%'>
													<tbody>
														<tr>
															<td>
																Name: $row[f_name]<br>
															</td>
															<td>
																Email: $row[email]<br>
															</td>
														</tr>
														<tr>
															<td>
																Address: $row[address]<br>
															</td>
															<td>
																City: $row[city]<br>
															</td>
														</tr>
														<tr>
															<td>
																State: $row[state]<br>
															</td>
														</tr>
													</tbody>
												</table>
												";
											?>
										</address>
									</div>
								</div>
								<div class="col-xl-3 col-lg-3 col-md-12 col-sm-12 col-12">
									<div class="invoice-details">
										<div class="invoice-num">
											<div>Invoice: <?php echo $orderid ?></div>
											<div>Time: <?php date_default_timezone_set('Asia/Ho_Chi_Minh');
														echo date('Y-m-d H:i:s') ?></div>
										</div>
									</div>
								</div>
							</div>
							<!-- Row end -->
						</div>
						<div class="invoice-body">
							<!-- Row start -->
							<div class="row gutters">
								<div class="col-lg-12 col-md-12 col-sm-12">
									<div class="table-responsive">
										<table class="table custom-table m-0">
											<thead>
												<tr>
													<th>Items</th>
													<th>Product ID</th>
													<th>Quantity</th>
													<th>Sub Total</th>
												</tr>
											</thead>
											<tbody>
												<?php
												$sql = "SELECT products.product_title, products.product_desc,products.product_id,qty,qty*products.product_price as subtotal FROM `order_products` 
												join orders_info on order_products.order_id = orders_info.order_id 
												join products on products.product_id = order_products.product_id 
												WHERE orders_info.order_id = '$orderid'";
												$rs = mysqli_query($con, $sql);
												$total = 0;
												while ($row = mysqli_fetch_array($rs)) {
													$total += (int)$row["subtotal"];
													echo "<tr>
														<td>
															$row[product_title]
															<p class='m-0 text-muted'>
																$row[product_desc].
															</p>
														</td>
														<td>#$row[product_id]</td>
														<td>$row[qty]</td>
														<td>$$row[subtotal]</td>
													</tr>";
												}
												?>

												<tr>
													<td>&nbsp;</td>
													<td colspan="2">
														<p>
															Subtotal<br>
														</p>
														<h5 class="text-success"><strong>Grand Total</strong></h5>
													</td>
													<td>
														<p>
															<?php echo "$$total"; ?><br>
														</p>
														<h5 class="text-success"><strong><?php echo "$$total"; ?></strong></h5>
													</td>
												</tr>
											</tbody>
										</table>
									</div>
								</div>
							</div>
							<!-- Row end -->
						</div>
						<div class="invoice-footer">
							Thank you for your Business.
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>