<?php
include('db_conn.php');
session_start();
refresh_cart();

// function login_user($username,$userpassword)
// {	
// 	$password = md5($userpassword);
// 	$conn = create_mysqli();
// 	$qry = "SELECT user_id FROM user_m WHERE user_email_id = '$username'";
// 	$qry1 = "SELECT user_email_id, user_password FROM user_m WHERE user_id = '$username'";
// 	$result = mysqli_query($conn,$qry1);
// 	$row = mysqli_num_rows($result);
// 	if($row > 0)
// 	{	
// 		session_start();
// 		$_SESSION['USER_EMAIL'] = $username;
// 		header('location: index.php');
// 	}
// 	else
// 	{
// 		return 1;
// 	}
// }

function login_user($username, $userpassword)
{
	$uid = user_email($username, $userpassword);
	if ($uid != 0) {
		$_SESSION['USER_ID_VERIFICATION'] = $uid;
		$_SESSION['cart'] = 0;
		$_SESSION['user_order'] = 0;
		$_SESSION['cart_prod'] = array();
		echo "<script> window.location.href='dashboard'; </script>";
		$_SESSION['cart_prod'];
		header('location: dashboard');
	} else {
		return 1;
	}
}

function user_email($username, $userpassword)
{
	$conn = create_conn();
	$sql = "SELECT user_id FROM user_m WHERE user_email = '$username'";
	$result = $conn->query($sql);
	$row = $result->fetch_assoc();
	if ($result->num_rows > 0) {
		return user_password($row['user_id'], $userpassword);
	} else {
		close_conn($conn);
		return 0;
	}
}

function user_password($user_id, $userpassword)
{
	$conn = create_conn();
	$password = md5($userpassword);
	$sql = "SELECT user_password FROM user_m WHERE user_id = '$user_id'";
	$result = $conn->query($sql);
	$row = $result->fetch_assoc();
	if ($result->num_rows > 0) {
		$v_pass = $row['user_password'];
		if ($v_pass == $password) {
			close_conn($conn);
			return $user_id;
		} else {
			close_conn($conn);
			return 0;
		}
	}
}

function register_client($ufname, $ulname, $cus_email, $cus_pass, $cus_c_pass)
{
	$conn = create_mysqli();
	if ($cus_c_pass == $cus_pass) {
		$password = md5($cus_pass);
		$sql = "INSERT INTO user_m (user_id, user_first_name, user_last_name, user_email, user_password, date_c, date_m) VALUES (NULL, '" . $ufname . "', '" . $ulname . "', '" . $cus_email . "', '" . $password . "', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);";
		$sql2 = "SELECT user_email FROM user_m WHERE user_email='$cus_email'";
		$result = mysqli_query($conn, $sql2);
		$row = mysqli_num_rows($result);
		$_SESSION['EMAIL'] = $cus_email;
		if ($row > 0) {
			return 0;
		} else {
			if (mysqli_query($conn, $sql)) {
				echo "true";
			} else {
				return 2;
			}
			close_mysqli($conn);
			return 1;
		}
	} else {
		return 3;
	}
}

// function update_client($fname, $lname, $user_email, $user_mobile, $user_picture, $user_id_email)
// {
// 	$conn = create_mysqli();
// 	$files = $_FILES['userpicture'];
// 	$filename = $files['name'];
// 	// $fileerror = $files['error'];
// 	$filetemp = $files['tmp_name'];
// 	$fileext = explode('.', $filename);
// 	$filecheck = strtolower(end($fileext));
// 	$fileextstored = array('png', 'jpg', 'jpeg');
// 	if (in_array($filecheck, $fileextstored)) {
// 		session_start();
// 		$destination = 'uploads/' . $filename;
// 		move_uploaded_file($filetemp, $destination);
// 		$sql = "UPDATE user_m SET  first_name = '" . $fname . "', last_name = '" . $lname . "', user_email_id = '" . $user_email . "', user_mobile = '" . $user_mobile . "',user_profile = '" . $destination . "' WHERE user_id = '$user_id_email'";
// 		$sql2 = "SELECT user_email_id FROM user_m WHERE user_email_id='$user_email'";
// 		$result = mysqli_query($conn, $sql2);
// 		$row = mysqli_num_rows($result);
// 		if ($row > 0) {
// 			return 0;
// 		} else {
// 			if (mysqli_query($conn, $sql)) {
// 				echo "true";
// 			} else {
// 				return 2;
// 			}
// 			close_mysqli($conn);
// 			return 1;
// 		}
// 	}
// 	return 4;
// }

function GET_UserName($userid)
{
	$conn = create_conn();
	$sql = "SELECT user_first_name, user_last_name FROM user_m WHERE user_id = '$userid'";
	$result = mysqli_query($conn, $sql);
	$row = mysqli_fetch_assoc($result);
	if ($result->num_rows > 0) {
		return $row['user_first_name'] . " " . $row['user_last_name'];
	} else {
		close_conn($conn);
		return 0;
	}
}

function GET_UserPic($userid)
{
	$conn = create_conn();
	$sql = "SELECT user_image FROM user_m WHERE user_id = '$userid'";
	$result = $conn->query($sql);
	$row = $result->fetch_assoc();
	if ($result->num_rows > 0) {
		return $row['user_image'];
	} else {
		close_conn($conn);
		return 0;
	}
}

function GET_UserEmail($userid)
{
	$conn = create_conn();
	$sql = "SELECT user_email FROM user_m WHERE user_id = '$userid'";
	$result = mysqli_query($conn, $sql);
	$row = mysqli_fetch_assoc($result);
	if ($result->num_rows > 0) {
		return $row['user_email'];
	} else {
		close_conn($conn);
		return 0;
	}
}

function GET_UserPhone($userid)
{
	$conn = create_conn();
	$sql = "SELECT user_mobile FROM user_m WHERE user_id = '$userid'";
	$result = mysqli_query($conn, $sql);
	$row = mysqli_fetch_assoc($result);
	if ($result->num_rows > 0) {
		return $row['user_mobile'];
	} else {
		close_conn($conn);
		return 0;
	}
}

// Load User Data At Home Page -- Out Products

function load_prod_sanitizer()
{
	$conn = create_conn();
	$query = "SELECT  product_id, product_name, product_image, product_price FROM product_m WHERE p_category_id = 1";
	$result = mysqli_query($conn, $query);
	while ($row = mysqli_fetch_assoc($result)) {
		echo "
		<div class=\"col-sm-6 col-md-4 col-lg-3 p-b-50\">
								
								<div class=\"block2\">
									<div class=\"block2-img wrap-pic-w of-hidden pos-relative\">
									<a href=\"product-detail?product_id=" . $row['product_id'] . "\"><img src=\"" . $row['product_image'] . "\" alt=\"IMG-PRODUCT\"></a>

										<div class=\"block2-overlay trans-0-4\">
											<a href=\"#\" class=\"block2-btn-addwishlist hov-pointer trans-0-4\">
												<i class=\"icon-wishlist icon_heart_alt\" aria-hidden=\"true\"></i>
												<i class=\"icon-wishlist icon_heart dis-none\" aria-hidden=\"true\"></i>
											</a>

											<div class=\"block2-btn-addcart w-size1 trans-0-4\">
												<button class=\"flex-c-m size1 bg4 bo-rad-23 hov1 s-text1 trans-0-4\">
													Add to Cart
												</button>
											</div>
										</div>
									</div>

									<div class=\"block2-txt p-t-20\">
										<a href=\"product-detail?product_id=" . $row['product_id'] . "\" class=\"block2-name dis-block s-text3 p-b-5\">
										 " . $row['product_name'] . "
										</a>

										<span class=\"block2-price m-text6 p-r-5\">
										₹" . $row['product_price'] . "
										</span>
									</div>
								</div>
							</div>
		";
	}
}

function load_prod_mask()
{
	$conn = create_conn();
	$query = "SELECT product_id, product_name, product_image, product_price FROM product_m WHERE p_category_id = 2";
	$result = mysqli_query($conn, $query);
	while ($row = mysqli_fetch_assoc($result)) {
		echo "
		<div class=\"col-sm-6 col-md-4 col-lg-3 p-b-50\">
								<div class=\"block2\">
									<div class=\"block2-img wrap-pic-w of-hidden pos-relative\">
									<a href=\"product-detail?product_id=" . $row['product_id'] . "\"><img src=\"" . $row['product_image'] . "\" alt=\"IMG-PRODUCT\"></a>

										<div class=\"block2-overlay trans-0-4\">
											<a href=\"#\" class=\"block2-btn-addwishlist hov-pointer trans-0-4\">
												<i class=\"icon-wishlist icon_heart_alt\" aria-hidden=\"true\"></i>
												<i class=\"icon-wishlist icon_heart dis-none\" aria-hidden=\"true\"></i>
											</a>

											<div class=\"block2-btn-addcart w-size1 trans-0-4\">
												<button class=\"flex-c-m size1 bg4 bo-rad-23 hov1 s-text1 trans-0-4\">
													Add to Cart
												</button>
											</div>
										</div>
									</div>

									<div class=\"block2-txt p-t-20\">
										<a href=\"product-detail?product_id=" . $row['product_id'] . "\" class=\"block2-name dis-block s-text3 p-b-5\">
										" . $row['product_name'] . "
										</a>
										<span class=\"block2-newprice m-text6 p-r-5\">
											₹" . $row['product_price'] . "
										</span>
									</div>
								</div>
							</div>
		";
	}
}

function load_prod_gloves()
{
	$conn = create_conn();
	$query = "SELECT product_id, product_name, product_image, product_price FROM product_m WHERE p_category_id = 3";
	$result = mysqli_query($conn, $query);
	while ($row = mysqli_fetch_assoc($result)) {
		echo "
		<div class=\"col-sm-6 col-md-4 col-lg-3 p-b-50\">
								<div class=\"block2\">
									<div class=\"block2-img wrap-pic-w of-hidden pos-relative\">
									<a href=\"product-detail?product_id=" . $row['product_id'] . "\"><img src=\"" . $row['product_image'] . "\" alt=\"IMG-PRODUCT\">
									</a>

										<div class=\"block2-overlay trans-0-4\">
											<a href=\"#\" class=\"block2-btn-addwishlist hov-pointer trans-0-4\">
												<i class=\"icon-wishlist icon_heart_alt\" aria-hidden=\"true\"></i>
												<i class=\"icon-wishlist icon_heart dis-none\" aria-hidden=\"true\"></i>
											</a>

											<div class=\"block2-btn-addcart w-size1 trans-0-4\">
												<button class=\"flex-c-m size1 bg4 bo-rad-23 hov1 s-text1 trans-0-4\">
													Add to Cart
												</button>
											</div>
										</div>
									</div>

									<div class=\"block2-txt p-t-20\">
										<a href=\"product-detail?product_id=" . $row['product_id'] . "\" class=\"block2-name dis-block s-text3 p-b-5\">
										" . $row['product_name'] . "
										</a>
										<span class=\"block2-newprice m-text6 p-r-5\">
											₹" . $row['product_price'] . "
										</span>
									</div>
								</div>
							</div>
		";
	}
}


function load_prod_glasses()
{
	$conn = create_conn();
	$query = "SELECT product_id, product_name, product_image, product_price FROM product_m WHERE p_category_id = 5";
	$result = mysqli_query($conn, $query);
	while ($row = mysqli_fetch_assoc($result)) {
		echo "
		<div class=\"col-sm-6 col-md-4 col-lg-3 p-b-50\">
								<div class=\"block2\">
									<div class=\"block2-img wrap-pic-w of-hidden pos-relative\">
									<a href=\"product-detail?product_id=" . $row['product_id'] . "\"><img src=\"" . $row['product_image'] . "\" alt=\"IMG-PRODUCT\"></a>

										<div class=\"block2-overlay trans-0-4\">
											<a href=\"#\" class=\"block2-btn-addwishlist hov-pointer trans-0-4\">
												<i class=\"icon-wishlist icon_heart_alt\" aria-hidden=\"true\"></i>
												<i class=\"icon-wishlist icon_heart dis-none\" aria-hidden=\"true\"></i>
											</a>

											<div class=\"block2-btn-addcart w-size1 trans-0-4\">
												<button class=\"flex-c-m size1 bg4 bo-rad-23 hov1 s-text1 trans-0-4\">
													Add to Cart
												</button>
											</div>
										</div>
									</div>

									<div class=\"block2-txt p-t-20\">
										<a href=\"product-detail?product_id=" . $row['product_id'] . "\" class=\"block2-name dis-block s-text3 p-b-5\">
										" . $row['product_name'] . "
										</a>
										<span class=\"block2-newprice m-text6 p-r-5\">
											₹" . $row['product_price'] . "
										</span>
									</div>
								</div>
							</div>
		";
	}
}

function load_prod_ppekit()
{
	$conn = create_conn();
	$query = "SELECT product_id, product_name, product_image, product_price FROM product_m WHERE p_category_id = 4";
	$result = mysqli_query($conn, $query);
	while ($row = mysqli_fetch_assoc($result)) {
		echo "
		<div class=\"col-sm-6 col-md-4 col-lg-3 p-b-50\">
								<div class=\"block2\">
									<div class=\"block2-img wrap-pic-w of-hidden pos-relative\">
									<a href=\"product-detail?product_id=" . $row['product_id'] . "\"><img src=\"" . $row['product_image'] . "\" alt=\"IMG-PRODUCT\"></a>

										<div class=\"block2-overlay trans-0-4\">
											<a href=\"#\" class=\"block2-btn-addwishlist hov-pointer trans-0-4\">
												<i class=\"icon-wishlist icon_heart_alt\" aria-hidden=\"true\"></i>
												<i class=\"icon-wishlist icon_heart dis-none\" aria-hidden=\"true\"></i>
											</a>

											<div class=\"block2-btn-addcart w-size1 trans-0-4\">
												<button class=\"flex-c-m size1 bg4 bo-rad-23 hov1 s-text1 trans-0-4\">
													Add to Cart
												</button>
											</div>
										</div>
									</div>

									<div class=\"block2-txt p-t-20\">
										<a href=\"product-detail?product_id=" . $row['product_id'] . "\" class=\"block2-name dis-block s-text3 p-b-5\">
										" . $row['product_name'] . "
										</a>
										<span class=\"block2-newprice m-text6 p-r-5\">
											₹" . $row['product_price'] . "
										</span>
									</div>
								</div>
							</div>
		";
	}
}

function load_prod_faceshield()
{
	$conn = create_conn();
	$query = "SELECT product_id, product_name, product_image, product_price FROM product_m WHERE p_category_id = 6";
	$result = mysqli_query($conn, $query);
	while ($row = mysqli_fetch_assoc($result)) {
		echo "
		<div class=\"col-sm-6 col-md-4 col-lg-3 p-b-50\">
								<div class=\"block2\">
									<div class=\"block2-img wrap-pic-w of-hidden pos-relative\">
									<a href=\"product-detail?product_id=" . $row['product_id'] . "\"><img src=\"" . $row['product_image'] . "\" alt=\"IMG-PRODUCT\"></a>

										<div class=\"block2-overlay trans-0-4\">
											<a href=\"#\" class=\"block2-btn-addwishlist hov-pointer trans-0-4\">
												<i class=\"icon-wishlist icon_heart_alt\" aria-hidden=\"true\"></i>
												<i class=\"icon-wishlist icon_heart dis-none\" aria-hidden=\"true\"></i>
											</a>

											<div class=\"block2-btn-addcart w-size1 trans-0-4\">
												<button class=\"flex-c-m size1 bg4 bo-rad-23 hov1 s-text1 trans-0-4\">
													Add to Cart
												</button>
											</div>
										</div>
									</div>

									<div class=\"block2-txt p-t-20\">
										<a href=\"product-detail?product_id=" . $row['product_id'] . "\" class=\"block2-name dis-block s-text3 p-b-5\">
										" . $row['product_name'] . "
										</a>
										<span class=\"block2-newprice m-text6 p-r-5\">
											₹" . $row['product_price'] . "
										</span>
									</div>
								</div>
							</div>
		";
	}
}

function load_product_details($prod_id)
{
	$conn = create_conn();
	$query = "SELECT a.p_category_id, a.p_category_name, b.product_id , b.product_name, b.product_image, b.product_price, b.product_desc FROM p_category_m a INNER JOIN product_m b ON a.p_category_id = b.p_category_id WHERE b.product_id = $prod_id";
	$qry = "SELECT p_category_id FROM product_m WHERE p_category_id = 2 OR p_category_id = 3";
	$result = mysqli_query($conn, $query);
	$rs = mysqli_query($conn, $qry);
	$row = mysqli_fetch_assoc($result);
	echo "
	<div class=\"w-size13 p-t-30 respon5\">
	<div class=\"wrap-slick3 flex-sb flex-w\">
		<div class=\"wrap-slick3-dots\"></div>

		<div class=\"slick3\">
			<div class=\"item-slick3\" data-thumb=\"" . $row['product_image'] . "\">
				<div class=\"wrap-pic-w\">
					<img src=\"" . $row['product_image'] . "\" alt=\"IMG-PRODUCT\">
				</div>
			</div>
		</div>
	</div>
</div>

<div class=\"w-size14 p-t-30 respon5\">
	<h4 class=\"product-detail-name m-text16 p-b-13\">
	" . $row['product_name'] . "
	</h4>

	<span class=\"m-text17\">
		₹" . $row['product_price'] . "
	</span>

	<p class=\"s-text8 p-t-10\">
		Nulla eget sem vitae eros pharetra viverra. Nam vitae luctus ligula. Mauris consequat ornare feugiat.
	</p>

	<!--  -->
	<div class=\"p-t-33 p-b-60\">
		<div class=\"flex-m flex-w p-b-10\">
			<div class=\"s-text15 w-size15 t-center\">
				Size
			</div>
			
			<div class=\"rs2-select2 rs3-select2 bo4 of-hidden w-size16\">
				<select class=\"selection-2\" name=\"size\">
					<option>Choose an option</option>
					<option>Size S</option>
					<option>Size M</option>
					<option>Size L</option>
					<option>Size XL</option>
				</select>
			</div>
		</div>

		<div class=\"flex-m flex-w\">
			<div class=\"s-text15 w-size15 t-center\">
				Color
			</div>

			<div class=\"rs2-select2 rs3-select2 bo4 of-hidden w-size16\">
				<select class=\"selection-2\" name=\"color\">
					<option>Choose an option</option>
					<option>Gray</option>
					<option>Red</option>
					<option>Black</option>
					<option>Blue</option>
				</select>
			</div>
		</div>
	  <form action=\"product-detail?product_id=$prod_id\" method=\"post\">
		<div class=\"flex-r-m flex-w p-t-10\">
			<div class=\"w-size16 flex-m flex-w\">
				<div class=\"flex-w bo5 of-hidden m-r-22 m-t-10 m-b-10\">
					<button class=\"btn-num-product-down color1 flex-c-m size7 bg8 eff2\">
						<i class=\"fs-12 fa fa-minus\" aria-hidden=\"true\"></i>
					</button>

					<input class=\"size8 m-text18 t-center num-product\" type=\"number\" name=\"prod_quant\" value=\"1\">

					<button class=\"btn-num-product-up color1 flex-c-m size7 bg8 eff2\">
						<i class=\"fs-12 fa fa-plus\" aria-hidden=\"true\"></i>
					</button>
				</div>

				<div class=\"btn-addcart-product-detail size9 trans-0-4 m-t-10 m-b-10\">
					<!-- Button -->
					<input type=\"hidden\" value=\"$_GET[product_id]\" name=\"prod_id\">
					<button type=\"submit\" name=\"submit\" class=\"flex-c-m sizefull bg1 bo-rad-23 hov1 s-text1 trans-0-4\">
						Add to Cart
					</button>
				</div>
			</div>
		</div>
	   </form>
	</div>

	<div class=\"p-b-45\">
		<span class=\"s-text8 m-r-35\">SKU: MUG-01</span>
		<span class=\"s-text8\">Categories: " . $row['p_category_name'] . "</span>
	</div>

	<!--  -->
	<div class=\"wrap-dropdown-content bo6 p-t-15 p-b-14 active-dropdown-content\">
		<h5 class=\"js-toggle-dropdown-content flex-sb-m cs-pointer m-text19 color0-hov trans-0-4\">
			Description
			<i class=\"down-mark fs-12 color1 fa fa-minus dis-none\" aria-hidden=\"true\"></i>
			<i class=\"up-mark fs-12 color1 fa fa-plus\" aria-hidden=\"true\"></i>
		</h5>

		<div class=\"dropdown-content dis-none p-t-15 p-b-23\">
			<p class=\"s-text8\">
			" . $row['product_desc'] . "
			</p>
		</div>
	</div>

	<div class=\"wrap-dropdown-content bo7 p-t-15 p-b-14\">
		<h5 class=\"js-toggle-dropdown-content flex-sb-m cs-pointer m-text19 color0-hov trans-0-4\">
			Additional information
			<i class=\"down-mark fs-12 color1 fa fa-minus dis-none\" aria-hidden=\"true\"></i>
			<i class=\"up-mark fs-12 color1 fa fa-plus\" aria-hidden=\"true\"></i>
		</h5>

		<div class=\"dropdown-content dis-none p-t-15 p-b-23\">
			<p class=\"s-text8\">
				Fusce ornare mi vel risus porttitor dignissim. Nunc eget risus at ipsum blandit ornare vel sed velit. Proin gravida arcu nisl, a dignissim mauris placerat
			</p>
		</div>
	</div>

	<div class=\"wrap-dropdown-content bo7 p-t-15 p-b-14\">
		<h5 class=\"js-toggle-dropdown-content flex-sb-m cs-pointer m-text19 color0-hov trans-0-4\">
			Reviews (0)
			<i class=\"down-mark fs-12 color1 fa fa-minus dis-none\" aria-hidden=\"true\"></i>
			<i class=\"up-mark fs-12 color1 fa fa-plus\" aria-hidden=\"true\"></i>
		</h5>

		<div class=\"dropdown-content dis-none p-t-15 p-b-23\">
			<p class=\"s-text8\">
				Fusce ornare mi vel risus porttitor dignissim. Nunc eget risus at ipsum blandit ornare vel sed velit. Proin gravida arcu nisl, a dignissim mauris placerat
			</p>
		</div>
	</div>
</div>
	";
	return $row['p_category_id'];
}

function add_to_cart($prod_id)
{
	$conn = create_conn();
	if (isset($_SESSION['USER_ID_VERIFICATION']) == true) {
		$query = "INSERT INTO cart_t (user_id, product_id, p_qty) VALUES ({$_SESSION['USER_ID_VERIFICATION']}, $prod_id, {$_POST['prod_quant']})";
		$fetch_p_id = "SELECT product_id FROM cart_t WHERE user_id = {$_SESSION['USER_ID_VERIFICATION']} AND product_id = $prod_id";
		$result_cat = mysqli_query($conn, $fetch_p_id);
		$row_cat = mysqli_num_rows($result_cat);
		echo "<script> console.log($row_cat); </script>";
		if ($row_cat > 0) {
			$query_u = "UPDATE cart_t SET p_qty = {$_POST['prod_quant']} WHERE user_id = {$_SESSION['USER_ID_VERIFICATION']} AND product_id = $prod_id";
			mysqli_query($conn, $query_u);
		} else {
			if (mysqli_query($conn, $query)) {
			}
		}
	} else {
		echo "<script> window.location.href = \"login\"; </script>";
	}
	refresh_cart();
}

function refresh_cart()
{
	$conn = create_conn();
	if (isset($_SESSION['cart'])) {
		$_SESSION['cart_prod'] = array();
		$res = mysqli_query($conn, "SELECT user_id, product_id FROM cart_t WHERE user_id = {$_SESSION['USER_ID_VERIFICATION']}");
		$_SESSION['cart'] = mysqli_num_rows($res);
	}
}

function user_order_tab()
{
	$conn = create_conn();
	$qry_u = mysqli_query($conn, "SELECT order_id FROM order_m WHERE user_id = {$_SESSION['USER_ID_VERIFICATION']}");
	$result = mysqli_num_rows($qry_u);
	return $result;
}

function load_address_cart()
{
	$conn = create_conn();
	$row = mysqli_fetch_assoc(mysqli_query($conn, "SELECT user_address FROM user_m WHERE user_id = {$_SESSION['USER_ID_VERIFICATION']}"));
	echo "<input type=\"hidden\" name=\"add_id\" value=\"{$row['user_address']}\">";
	echo "{$row['user_address']}";
}

function load_cart_total()
{
	$conn = create_conn();
	$cart_total = 0;
	$qry = mysqli_query($conn, "SELECT a.p_qty*b.product_price AS total FROM cart_t a INNER JOIN product_m b ON a.product_id = b.product_id WHERE a.user_id = {$_SESSION['USER_ID_VERIFICATION']}");
	while ($row = mysqli_fetch_assoc($qry)) {
		$cart_total += $row['total'];
	}
	return $cart_total;
}

function fetch_product_name($prod_id)
{
	$conn = create_conn();
	$query = "SELECT a.p_category_id, a.p_category_name, b.product_id , b.product_name, b.product_image, b.product_price, b.product_desc FROM p_category_m a INNER JOIN product_m b ON a.p_category_id = b.p_category_id WHERE b.product_id = $prod_id";
	$result = mysqli_query($conn, $query);
	$row = mysqli_fetch_assoc($result);
	echo $row['product_name'];
}

function fetch_product_category_name($prod_id)
{
	$conn = create_conn();
	$query = "SELECT a.p_category_id, a.p_category_name, b.product_id , b.product_name, b.product_image, b.product_price, b.product_desc FROM p_category_m a INNER JOIN product_m b ON a.p_category_id = b.p_category_id WHERE b.product_id = $prod_id";
	$result = mysqli_query($conn, $query);
	$row = mysqli_fetch_assoc($result);
	echo "<a href=\"product?p_cat_id=" . $row['p_category_id'] . "\" class=\"s-text16\">
				" . $row['p_category_name'] . "
			<i class=\"fa fa-angle-right m-l-8 m-r-9\" aria-hidden=\"true\"></i>
		</a>
		";
}

function load_related_product($prod_cate_id, $prod_id)
{
	$conn = create_conn();
	$query = "SELECT product_id, product_name, product_image, product_price FROM product_m WHERE p_category_id = $prod_cate_id AND product_id != $prod_id";
	$result = mysqli_query($conn, $query);
	while ($row = mysqli_fetch_assoc($result)) {
		echo "
		<div class=\"item-slick2 p-l-15 p-r-15\">
						<div class=\"block2\">
							<div class=\"block2-img wrap-pic-w of-hidden pos-relative block2-labelnew\">
								<img src=\"" . $row['product_image'] . "\" alt=\"IMG-PRODUCT\">

								<div class=\"block2-overlay trans-0-4\">
									<a href=\"#\" class=\"block2-btn-addwishlist hov-pointer trans-0-4\">
										<i class=\"icon-wishlist icon_heart_alt\" aria-hidden=\"true\"></i>
										<i class=\"icon-wishlist icon_heart dis-none\" aria-hidden=\"true\"></i>
									</a>

									<div class=\"block2-btn-addcart w-size1 trans-0-4\">
										<!-- Button -->
										<button class=\"flex-c-m size1 bg4 bo-rad-23 hov1 s-text1 trans-0-4\">
											Add to Cart
										</button>
									</div>
								</div>
							</div>

							<div class=\"block2-txt p-t-20\">
								<a href=\"product-detail?product_id=" . $row['product_id'] . "\" class=\"block2-name dis-block s-text3 p-b-5\">
								" . $row['product_name'] . "
								</a>

								<span class=\"block2-price m-text6 p-r-5\">
								₹ " . $row['product_price'] . "
								</span>
							</div>
						</div>
					</div>
		";
	}
}

function product_category_name()
{
	$conn = create_conn();
	$query = "SELECT p_category_id, p_category_name FROM p_category_m";
	$result_cat = mysqli_query($conn, $query);
	if (!isset($_GET['p_cat_id'])) {
		echo "
			<li class=\"p-t-4\">
				<a href=\"product\" class=\"s-text13 active1\">
					All
				</a>
			</li>";

		while ($row_cat = mysqli_fetch_assoc($result_cat)) {
			echo "
				<li class=\"p-t-4\">
					<a href=\"product?p_cat_id=" . $row_cat['p_category_id'] . "\" class=\"s-text13\">
						" . $row_cat['p_category_name'] . "
					</a>
				</li>";
		}
	} else {
		echo "
			<li class=\"p-t-4\">
				<a href=\"product\" class=\"s-text13\">
					All
				</a>
			</li>";

		while ($row_cat = mysqli_fetch_assoc($result_cat)) {
			if ($row_cat['p_category_id'] == $_GET['p_cat_id']) {
				echo "
					<li class=\"p-t-4\">
						<a href=\"product?p_cat_id=" . $row_cat['p_category_id'] . "\" class=\"s-text13 active1\">
							" . $row_cat['p_category_name'] . "
						</a>
					</li>";
			} else {
				echo "
					<li class=\"p-t-4\">
						<a href=\"product?p_cat_id=" . $row_cat['p_category_id'] . "\" class=\"s-text13\">
							" . $row_cat['p_category_name'] . "
						</a>
					</li>";
			}
		}
	}
}

function load_prod_list()
{
	$conn = create_conn();
	if (isset($_GET['p_cat_id'])) {
		$p_cat_id = $_GET['p_cat_id'];
		$query = "SELECT product_id, product_name, product_image, product_price FROM product_m WHERE p_category_id = $p_cat_id";
	} else {
		$query = "SELECT product_id, product_name, product_image, product_price FROM product_m";
	}
	$result = mysqli_query($conn, $query);

	while ($row = mysqli_fetch_assoc($result)) {
		echo "
			<div class=\"col-sm-12 col-md-6 col-lg-4 p-b-50\">
				<!-- block -->
				<div class=\"block2\">
					<div class=\"block2-img wrap-pic-w of-hidden pos-relative\">
						<a href=\"product-detail?product_id=" . $row['product_id'] . "\"><img src=\"" . $row['product_image'] . "\" alt=\"IMG-PRODUCT\"></a>

						<div class=\"block2-overlay trans-0-4\">
							<div class=\"block2-btn-addcart w-size1 trans-0-4\">
								<!-- Button -->
								<button onclick=\"window.location.href='product-detail?product_id=" . $row['product_id'] . "'\" class=\"flex-c-m size1 bg4 bo-rad-23 hov1 s-text1 trans-0-4\">
									ADD TO CART
								</button>
							</div>
						</div>
					</div>

					<div class=\"block2-txt p-t-20\">
						<a href=\"product-detail?product_id=" . $row['product_id'] . "\" class=\"block2-name dis-block s-text3 p-b-5\">
							" . $row['product_name'] . "
						</a>
						<span class=\"block2-price m-text6 p-r-5\">
							 ₹" . $row['product_price'] . "
						</span>
					</div>
				</div>
			</div>";
	}
}

// <i class=\"fa fa-rupee\" style=\"font-size:18px\"></i>

function load_search_results($p_name)
{
	$conn = create_conn();
	$query = "SELECT product_id, product_name, product_image, product_price FROM product_m WHERE product_name LIKE '%{$p_name}%'";
	$result = mysqli_query($conn, $query);
	while ($row = mysqli_fetch_assoc($result)) {
		echo "
		<div class=\"col-sm-12 col-md-6 col-lg-4 p-b-50\">
		<!-- block -->
		<div class=\"block2\">
			<div class=\"block2-img wrap-pic-w of-hidden pos-relative\">
				<a href=\"product-detail?product_id=" . $row['product_id'] . "\"><img src=\"" . $row['product_image'] . "\" alt=\"IMG-PRODUCT\"></a>

				<div class=\"block2-overlay trans-0-4\">
					<div class=\"block2-btn-addcart w-size1 trans-0-4\">
						<!-- Button -->
						<button onclick=\"window.location.href='product-detail?product_id=" . $row['product_id'] . "'\" class=\"flex-c-m size1 bg4 bo-rad-23 hov1 s-text1 trans-0-4\">
							ADD TO CART
						</button>
					</div>
				</div>
			</div>

			<div class=\"block2-txt p-t-20\">
				<a href=\"product-detail?product_id=" . $row['product_id'] . "\" class=\"block2-name dis-block s-text3 p-b-5\">
					" . $row['product_name'] . "
				</a>
				<span class=\"block2-price m-text6 p-r-5\">
					₹" . $row['product_price'] . "
				</span>
			</div>
		</div>
	</div>";
	}
}

// function cart_menu_drop()
// {
// 	$conn = create_conn();
// 	$query = "SELECT a.user_id, a.product_id, a.p_qty, b.product_name, b.product_image, b.product_price FROM cart_t a INNER JOIN product_m b ON a.product_id = b.product_id WHERE a.user_id = {$_SESSION['USER_ID_VERIFICATION']}";
// 	$result = mysqli_query($conn, $query);
// 	while ($row = mysqli_fetch_assoc($result)) {
// 		$price = $row['product_price'];
// 		$quant = $row['p_qty'];
// 		$total = $price * $quant;
// 		echo "
// 		<div class=\"header-cart header-dropdown\">
// 		<ul class=\"header-cart-wrapitem\">
// 			<li class=\"header-cart-item\">
// 				<div class=\"header-cart-item-img\">
// 					<img src=\"" . $row['product_image'] . "\" alt=\"IMG\">
// 				</div>

// 				<div class=\"header-cart-item-txt\">
// 					<a href=\"#\" class=\"header-cart-item-name\">
// 					" . $row['product_name'] . "
// 					</a>

// 					<span class=\"header-cart-item-info\">
// 						₹" . $row['product_price'] . "
// 					</span>
// 				</div>
// 			</li>
// 		</ul>

// 		<div class=\"header-cart-total\">
// 			Total: ₹$total
// 		</div>

// 		<div class=\"header-cart-buttons\">
// 			<div class=\"header-cart-wrapbtn\">
// 				<!-- Button -->
// 				<a href=\"cart\" class=\"flex-c-m size1 bg1 bo-rad-20 hov1 s-text1 trans-0-4\">
// 					View Cart
// 				</a>
// 			</div>

// 			<div class=\"header-cart-wrapbtn\">
// 				<!-- Button -->
// 				<a href=\"#\" class=\"flex-c-m size1 bg1 bo-rad-20 hov1 s-text1 trans-0-4\">
// 					Check Out
// 				</a>
// 			</div>
// 		</div>
// 	</div>
// 		";
// 	}
// }

function load_cart()
{
	$conn = create_conn();
	$query = "SELECT a.user_id, a.product_id, a.p_qty, b.product_name, b.product_image, b.product_price FROM cart_t a INNER JOIN product_m b ON a.product_id = b.product_id WHERE a.user_id = {$_SESSION['USER_ID_VERIFICATION']}";
	$result = mysqli_query($conn, $query);
	while ($row = mysqli_fetch_assoc($result)) {
		$price = $row['product_price'];
		$quant = $row['p_qty'];
		$total = $price * $quant;
		$str = "_" . $row['product_id'];
		echo "
		<tr class=\"table-row\">
		<td class=\"column-1\">
			<div class=\"cart-img-product b-rad-4 o-f-hidden\">
				<img src=\"" . $row['product_image'] . "\" alt=\"IMG-PRODUCT\">
			</div>
		</td>
		<td class=\"column-2\">" . $row['product_name'] . "</td>
		<td class=\"column-3\" style=\"color: #28B463;\"> ₹" . $row['product_price'] . "</td>
		<td class=\"column-4 p-l-70\">
			<div class=\"flex-w bo5 of-hidden w-size17\">
				<button class=\"btn-num-product-down color1 flex-c-m size7 bg8 eff2\" onclick=\"minus('{$str}');\">
					<i class=\"fs-12 fa fa-minus\" aria-hidden=\"true\"></i>
				</button>

				<input value=\"$quant\" class=\"size8 m-text18 t-center num-product\" type=\"number\" name=\"prod_quant_{$row['product_id']}\" id=\"prod_quant_{$row['product_id']}\" oninput=\"update_cart(this.value + '{$str}');\">

				<button class=\"btn-num-product-up color1 flex-c-m size7 bg8 eff2\"  onclick=\"add('{$str}');\">
					<i class=\"fs-12 fa fa-plus\" aria-hidden=\"true\"></i>
				</button>
			</div>
		</td>
		<td class=\"column-5\" id=\"total_{$row['product_id']}\" style=\"color: #28B463;\">₹$total</td>
	</tr>
			";
	}
}

function place_order()
{
	$conn = create_conn();
	$aid = $_POST['add_id'];
	$total = load_cart_total();

	$query = "INSERT INTO order_m (user_id, order_amount) VALUES ({$_SESSION['USER_ID_VERIFICATION']}, $total)";
	$abcd = mysqli_query($conn, $query);
	echo "<script> console.log($abcd); </script>";
	$order_id = mysqli_insert_id($conn);

	$cart_res = mysqli_query($conn, "SELECT a.product_id, a.p_qty, b.product_price FROM cart_t a INNER JOIN product_m b ON a.product_id = b.product_id WHERE a.user_id = {$_SESSION['USER_ID_VERIFICATION']}");

	while ($row = mysqli_fetch_assoc($cart_res)) {
		mysqli_query($conn, "INSERT INTO order_t (order_id, product_id, p_qty, product_price) VALUES ($order_id, {$row['product_id']}, {$row['p_qty']}, {$row['product_price']})");
	}
	// mysqli_query($conn, "DELETE FROM cart_t WHERE user_id = {$_SESSION['USER_ID_VERIFICATION']}");
	header('refresh:0, url=checkout');
}

function checkout_to_place_order()
{
	$conn = create_conn();
	mysqli_query($conn, "DELETE FROM cart_t WHERE user_id = {$_SESSION['USER_ID_VERIFICATION']}");
	header('refresh:0, url=myorder');
}

function update_quantity_cart_model($pq, $vid)
{
	$conn = create_mysqli();

	if (isset($_POST['vid']) and isset($_POST['pq'])) {
		$pq = $_POST['pq'];
		$vid = $_POST['vid'];

		if ($pq == 0) {
			mysqli_query($conn, "DELETE FROM cart_t WHERE product_id = $vid AND user_id = {$_SESSION['USER_ID_VERIFICATION']}");
		} else {
			mysqli_query($conn, "UPDATE cart_t SET p_qty = $pq WHERE product_id = $vid");
			$row = mysqli_fetch_assoc(mysqli_query($conn, "SELECT product_price FROM product_m WHERE product_id = $vid"));
			$tot = $pq * $row['product_price'];
			echo "₹" . $tot;
		}
	}
}

function fill_billing_details_user()
{
	$conn = create_mysqli();
	$query = "SELECT user_first_name, user_last_name, user_phone, user_address, user_email FROM user_m WHERE user_id = {$_SESSION['USER_ID_VERIFICATION']}";
	$result = mysqli_query($conn, $query);
	while ($row = mysqli_fetch_assoc($result)) {
		echo "
		
						<h3>Billing Details</h3>
						<form class=\"row\" action=\"#\" method=\"post\" novalidate=\"novalidate\">
							
							<div class=\"col-md-6 form-group\">
								<input type=\"text\" class=\"bg6 form-control\" id=\"first\" name=\"name\" placeholder=\"Enter first name\" value=\" " . $row['user_first_name'] . "\" />
								<span class=\"placeholder\" data-placeholder=\"First name\"></span>
							</div>
							<div class=\"col-md-6 form-group\">
								<input type=\"text\" class=\"bg6 form-control\" id=\"last\" name=\"name\" placeholder=\"Enter last name\" value=\" " . $row['user_last_name'] . "\" />
								<span class=\"placeholder\" data-placeholder=\"Last name\"></span>
							</div>
							<div class=\"col-md-12 form-group\">
								<input type=\"text\" class=\"bg6 form-control\" id=\"last\" name=\"name\" placeholder=\"Enter last name\" value=\" " . $row['user_address'] . "\" />
							</div>
							<div class=\"col-md-6 form-group\">
								<input type=\"text\" class=\"bg6 form-control\" id=\"uphone\" name=\"uphone\" placeholder=\"Enter phone number\" value=\" " . $row['user_phone'] . "\" />
								<span class=\"placeholder\" data-placeholder=\"Phone number\"></span>
							</div>
							<div class=\"col-md-6 form-group\">
								<input type=\"text\" class=\"bg6 form-control\" id=\"email\" name=\"compemailany\" placeholder=\"Enter Email Address\" value=\" " . $row['user_email'] . "\" />
								<span class=\"placeholder\" data-placeholder=\"Enter Email Address\"></span>
							</div>
						</form>
					
		";
	}
}

function fill_billing_details_order()
{
	$conn = create_mysqli();
	$query = "SELECT a.order_id, a.user_id, a.order_amount, b.product_id, b.p_qty, b.product_price, c.product_name FROM order_m a INNER JOIN order_t b ON a.order_id = b.order_id INNER JOIN product_m c ON b.product_id = c.product_id WHERE a.user_id = {$_SESSION['USER_ID_VERIFICATION']}";
	$result = mysqli_query($conn, $query);
	while ($row = mysqli_fetch_assoc($result)) {
		echo "
			<li>
				<a href=\"#\">" . $row['product_name'] . "
					<span class=\"last\">₹" . $row['product_price'] . "</span>
				</a>
			</li>		
		";
	}
}

function fill_billing_details_total()
{
	$conn = create_mysqli();
	$query = "SELECT a.order_id, a.user_id, a.order_amount, b.product_id, b.p_qty, b.product_price, c.product_name FROM order_m a INNER JOIN order_t b ON a.order_id = b.order_id INNER JOIN product_m c ON b.product_id = c.product_id WHERE a.user_id = {$_SESSION['USER_ID_VERIFICATION']}";
	$result = mysqli_query($conn, $query);
	$row = mysqli_fetch_assoc($result);
	echo $row['order_amount'];
}

function user_address_my_order_details()
{
	$conn = create_mysqli();
	$query = "SELECT user_address FROM user_m WHERE user_id = {$_SESSION['USER_ID_VERIFICATION']}";
	$result = mysqli_query($conn, $query);
	$row = mysqli_fetch_assoc($result);
	echo $row['user_address'];
}

function date_format_in($datevar)
{
	return date('d-m-Y', strtotime($datevar));
}

function load_orders()
{
	$conn = create_mysqli();
	$query = "SELECT order_id, order_amount, order_date AS order_dt FROM order_m WHERE user_id = {$_SESSION['USER_ID_VERIFICATION']} ORDER BY date_c DESC";
	$result = mysqli_query($conn, $query);
	while ($row = mysqli_fetch_assoc($result)) {
		echo "
			<tr>
				<td>{$row['order_id']}</td>
				<td>" . ($row['order_dt']) . "</td>
				<td>₹{$row['order_amount']}</td>
				<td><a class=\"btn btn-primary\" href=\"order-details?order_id={$row['order_id']}\">View</a></td>
			</tr>";
	}
}

function load_profile_db()
{
	$conn = create_mysqli();
	$query = "SELECT user_first_name, user_last_name, user_phone, user_address, user_email FROM user_m WHERE user_id = " . $_SESSION['USER_ID_VERIFICATION'] . "";
	$result = mysqli_query($conn, $query);
	$row = mysqli_fetch_assoc($result);
	return $row;
}

function load_order_details($order_id)
{
	$conn = create_mysqli();
	$row = mysqli_fetch_assoc(mysqli_query($conn, "SELECT order_amount, order_date, date_c FROM order_m WHERE order_id = $order_id "));
	$query1 = "SELECT product_id,  product_price, p_qty FROM order_t WHERE order_id = $order_id";
	$result1 = mysqli_query($conn, $query1);

	while ($row1 = mysqli_fetch_assoc($result1)) {
		$result2 = mysqli_query($conn, "SELECT product_id, product_image, product_name, product_price FROM product_m WHERE product_id = {$row1['product_id']}");
		$row2 = mysqli_fetch_assoc($result2);
		$price = $row1['product_price'];
		$quant = $row1['p_qty'];
		$total = $price * $quant;
		echo "
			<tr class=\"table-row\">
				<td class=\"column-1\">
					<div class=\"cart-img-product b-rad-4 o-f-hidden\">
						<img src=\"{$row2['product_image']}\" alt=\"IMG-PRODUCT\">
					</div>
				</td>
				<td class=\"column-4\"><a href=\"product-detail?product_id={$row2['product_id']}\">" . $row2['product_name'] . "<a></td>
				<td class=\"column-6\">₹{$row2['product_price']}</td>
				<td class=\"column-7\">{$row1['p_qty']}</td>
				<td class=\"column-8\">₹{$total}</td>
			</tr>";
	}
	return $row;
}

function update_client_data($fname, $lname, $user_mobile, $useraddress, $userimage, $userid)
{
	$conn = create_mysqli();
	$files = $_FILES['image'];
	$filename = $files['name'];
	$fileerror = $files['error'];
	$filetemp = $files['tmp_name'];
	$fileext = explode('.', $filename);
	$filecheck = strtolower(end($fileext));
	$fileextstored = array('png', 'jpg', 'jpeg');
	if (in_array($filecheck, $fileextstored)) {
		session_start();
		$destination = 'images/uploads/' . $filename;
		move_uploaded_file($filetemp, $destination);
		$sql = "UPDATE user_m SET  user_first_name = '" . $fname . "', user_last_name = '" . $lname . "', user_phone = '" . $user_mobile . "', user_address = '" . $useraddress . "', user_image = '" . $destination . "' WHERE user_id = '$userid'";
		$result = mysqli_query($conn, $sql);
		if ($result == true) {
			return 0;
		} else {
			return 2;
		}
	}
	return 4;
}

function UPDATE_UserProfile_Password($cpass, $npass, $userid)
{
	$tempvar = user_password($userid, $cpass);
	$npass_new = md5($npass);
	if ($tempvar == 0) {
		return 2;
	} else {
		$conn = create_conn();
		$sql = "UPDATE user_m SET user_password = '$npass_new' WHERE user_id = $userid";
		if (mysqli_query($conn, $sql)) {
			close_mysqli($conn);
			return 1;
			// header('location: dashboard.php?resp=1');
		} else {
			close_mysqli($conn);
			return 3;
			// header('location: dashboard.php?error=21');
		}
	}
}

// function update_profile($uid)
// {
// 	$conn = create_mysqli();
// 	$funame = $_POST['userfname'];
// 	$luname = $_POST['userlname'];
// 	$uphone = $_POST['userphonem'];
// 	$uemail = $_POST['useremailadd'];
// 	$uaddress = $_POST['useradd'];

// 	$files = $_FILES['userpicture'];
// 	$filename = $files['name'];
// 	$fileerror = $files['error'];
// 	$filetemp = $files['tmp_name'];
// 	$fileext = explode('.', $filename);
// 	$filecheck = strtolower(end($fileext));
// 	$fileextstored = array('png', 'jpg', 'jpeg');
// 	if (in_array($filecheck, $fileextstored)) {
// 		session_start();
// 		$destination = 'images/uploads/' . $filename;
// 		move_uploaded_file($filetemp, $destination);
// 		$sql_w_empt  = "UPDATE user_m SET  user_first_name = '" . $funame . "', user_last_name = '" . $luname . "', user_email = '" . $uemail . "', user_phone = '" . $uphone . "', user_address = '" . $uaddress . "', user_image = '" . $destination . "' WHERE user_id = '$uid'";
// 		$sql2 = "SELECT user_email FROM user_m WHERE user_email='$uemail'";
// 		$result = mysqli_query($conn, $sql2);
// 		$row = mysqli_num_rows($result);
// 		if ($row > 0) {
// 			return 0;
// 		} else {
// 			if (mysqli_query($conn, $sql_w_empt)) {
// 				echo "true";
// 			} else {
// 				return 2;
// 			}
// 			close_mysqli($conn);
// 			return 1;
// 		}
// 	}
// 	return 4;
// }

function update_profile_data($uid)
{
	$conn = create_mysqli();
	$funame = $_POST['userfname'];
	$luname = $_POST['userlname'];
	$uphone = $_POST['userphonem'];
	$uemail = $_POST['useremailadd'];
	$uaddress = $_POST['useradd'];

	$userdata = "UPDATE user_m SET  user_first_name = '" . $funame . "', user_last_name = '" . $luname . "', user_phone = '" . $uphone . "', user_address = '" . $uaddress . "' WHERE user_id = '$uid'";
	$res = mysqli_query($conn, $userdata);
	if ($res) {
		return 1;
	} else {
		return 2;
	}
	echo "<script> window.location.href='dashboard'; </script>";
}

function UPDATE_UserProfile_Picture($pic, $userid)
{
	$conn = create_conn();
	$files = $_FILES['uimage'];
	$filename = $files['name'];
	$fileerror = $files['error'];
	$filetemp = $files['tmp_name'];
	$fileext = explode('.', $filename);
	$filecheck = strtolower(end($fileext));
	$fileextstored = array('png', 'jpg', 'jpeg');
	if (in_array($filecheck, $fileextstored)) {
		$destination = 'images/uploads/'.$filename;
		move_uploaded_file($filetemp, $destination);
		$sql = "UPDATE user_m SET user_image = '" . $destination . "' WHERE user_id = '$userid'";
		$result = mysqli_query($conn, $sql);
		if ($result == true) {
			return 1;
		} else {
			return 2;
		}
	}
	return 4;
}

function customer_query_msg($ufname, $ulname, $uemail, $umsg)
{
	$conn = create_conn();
	if (isset($_SESSION['USER_ID_VERIFICATION'])) {
		$sql1 = "INSERT INTO user_query (user_id,user_q_fname,user_q_lname,user_q_email,user_q_msg,date_c,date_m) VALUES ({$_SESSION['USER_ID_VERIFICATION']}, '$ufname', '$ulname', '$uemail', '$umsg', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);";
		$result = mysqli_query($conn, $sql1);
		if ($result == true) {
			return 1;
		} else {
			return 2;
		}
	} else {
		return 3;
	}
}

function customer_change_password($cpass, $npass)
{
	// $tempvar = user_password($userid, $cpass);
	// $npass_new = md5($npass);
	// if ($tempvar == 0) {
	// 	return 2;
	// } else {
	// 	$conn = create_conn();
	// 	$sql = "UPDATE user_m SET user_password = '$npass_new' WHERE user_id = $userid";
	// 	if (mysqli_query($conn, $sql)) {
	// 		close_mysqli($conn);
	// 		return 1;
	// 		// header('location: dashboard.php?resp=1');
	// 	} else {
	// 		close_mysqli($conn);
	// 		return 3;
	// 		// header('location: dashboard.php?error=21');
	// 	}
	// }
}