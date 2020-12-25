<?php
include('base/functions.php');
if (isset($_SESSION['USER_ID_VERIFICATION']) == true) {
	header('location: dashboard');
	die();
} else {
	if (isset($_POST['customeremail']) and isset($_POST['customerpass'])) {
		if ($_POST['customeremail'] != NULL and $_POST['customerpass'] != NULL) {

			$response = login_user($_POST['customeremail'], $_POST['customerpass']);
			if ($response == 1) {
				header('location: login?error=10');
				die();
			}
		}
	}
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<title>Medic Store</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<?php include('includes/header.php'); ?>

	<!-- Slide1 -->
	<section class="slide1">
		<div class="limiter">
			<div class="container-login100">
				<div class="wrap-login100">
					<div class="login100-pic js-tilt" data-tilt>
						<img src="images/icons/logo_p.png" alt="IMG" class="login_img">
						<h3 style="font-size: 25px;">We Care for Your Health Every Moment</h3>
					</div>
					<!-- <div class="login100-pic_line js-tilt vl"></div> -->
					<form class="login100-form validate-form" method="post">
						<span class="login100-form-title">
							Login
							<hr />
						</span>
						<?php
						if (isset($_GET['error'])) {
							if ($_GET['error'] == 10) {
						?>
								<div class="alert alert-danger" role="alert">
									<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
									<strong>Login Faild !!!</strong><a href="#" class="alert-link"></a>
								</div>
						<?php
							}
						}
						?>
						<div class="wrap-input100 validate-input" data-validate="Valid email is required: ex@abc.xyz">
							<input class="input100" type="email" name="customeremail" id="customeremail" placeholder="Email" required>
							<span class="focus-input100"></span>
							<span class="symbol-input100">
								<i class="fa fa-envelope" aria-hidden="true"></i>
							</span>
						</div>

						<div class="wrap-input100 validate-input" data-validate="Password is required">
							<input class="input100" type="password" name="customerpass" id="customerpass" placeholder="Password" required>
							<span class="focus-input100"></span>
							<span class="symbol-input100">
								<i class="fa fa-lock" aria-hidden="true"></i>
							</span>
						</div>

						<div class="container-login100-form-btn">
							<button class="login100-form-btn" type="submit">
								Login
							</button>
						</div>

						<div class="text-center p-t-12">
							<span class="txt1">
								Forgot
							</span>
							<a class="txt2" href="forgot_password">
								Username / Password?
							</a>
						</div>

						<div class="text-center p-t-70">
							<a class="txt2" href="register">
								Create a new account?
								<i class="fa fa-long-arrow-right m-l-5" aria-hidden="true"></i>
							</a>
						</div>
					</form>
				</div>
			</div>
		</div>
	</section>

	<!-- Footer -->
	<?php include('includes/footer.php'); ?>

	<!-- Container Selection1 -->
	<div id="dropDownSelect1"></div>

	<!-- Modal Video 01-->
	<div class="modal fade" id="modal-video-01" tabindex="-1" role="dialog" aria-hidden="true">

		<div class="modal-dialog" role="document" data-dismiss="modal">
			<div class="close-mo-video-01 trans-0-4" data-dismiss="modal" aria-label="Close">&times;</div>

			<div class="wrap-video-mo-01">
				<div class="w-full wrap-pic-w op-0-0"><img src="images/icons/video-16-9.jpg" alt="IMG"></div>
				<div class="video-mo-01">
					<iframe src="https://www.youtube.com/embed/Nt8ZrWY2Cmk?rel=0&amp;showinfo=0" allowfullscreen></iframe>
				</div>
			</div>
		</div>
	</div>

	<!--===============================================================================================-->
	<script type="text/javascript" src="vendor/jquery/jquery-3.2.1.min.js"></script>
	<!--===============================================================================================-->
	<script type="text/javascript" src="vendor/animsition/js/animsition.min.js"></script>
	<!--===============================================================================================-->
	<script type="text/javascript" src="vendor/bootstrap/js/popper.js"></script>
	<script type="text/javascript" src="vendor/bootstrap/js/bootstrap.min.js"></script>
	<!--===============================================================================================-->
	<script type="text/javascript" src="vendor/select2/select2.min.js"></script>
	<script type="text/javascript">
		$(".selection-1").select2({
			minimumResultsForSearch: 20,
			dropdownParent: $('#dropDownSelect1')
		});
	</script>
	<!--===============================================================================================-->
	<script type="text/javascript" src="vendor/slick/slick.min.js"></script>
	<script type="text/javascript" src="js/slick-custom.js"></script>
	<!--===============================================================================================-->
	<script type="text/javascript" src="vendor/countdowntime/countdowntime.js"></script>
	<!--===============================================================================================-->
	<script type="text/javascript" src="vendor/lightbox2/js/lightbox.min.js"></script>
	<!--===============================================================================================-->
	<script type="text/javascript" src="vendor/sweetalert/sweetalert.min.js"></script>

	<!--===============================================================================================-->
	<script type="text/javascript" src="vendor/parallax100/parallax100.js"></script>
	<script type="text/javascript">
		$('.parallax100').parallax100();
	</script>
	<!--===============================================================================================-->
	<script src="js/main.js"></script>

	</body>

</html>