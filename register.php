<?php
include_once('base/functions.php');
if (isset($_SESSION['USER_ID_VERIFICATION']) == true) {
	header('location: index');
	die();
} else {
	if (isset($_POST['userfname']) and isset($_POST['userlname']) and isset($_POST['customeremail']) and isset($_POST['customerpass']) and isset($_POST['customercpass'])) {
		if ($_POST['userfname'] != NULL and $_POST['userlname'] != NULL and $_POST['customeremail'] != NULL and $_POST['customerpass'] != NULL and $_POST['customercpass'] != NULL) {
			$response = register_client($_POST['userfname'],$_POST['userlname'],$_POST['customeremail'], $_POST['customerpass'], $_POST['customercpass']);

			if ($response == 1) {
				header('location: login');
				die();
			} else if ($response == 0) {
				header('location: register?error=10');
				die();
			} else if ($response == 3) {
				header('location: register?error=21');
				die();
			} 
		}
	}
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<title>Medic Store | Sign In Here</title>
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
						<h3>We Care for Your Health Every Moment</h3>
					</div>
					<form class="login100-form validate-form" method="post">
						<span class="login100-form-title">
							Sign In
							<hr />
						</span>
						<?php if (isset($_GET['error'])) {
							if ($_GET['error'] == 10) {
						?>
								<div class="alert alert-danger" role="alert">
								<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
									Email is already registered !!!<a href="#" class="alert-link"></a>
								</div>
								<?php
							} else {
								if ($_GET['error'] == 21) { ?>
									<div class="alert alert-danger" role="alert">
									<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
									Password is not matched !!!<a href="#" class="alert-link"></a>
									</div>
									<?php
								} else {
									if ($_GET['error'] == 22) { ?>
										<div class="alert alert-danger" role="alert">
											<div class="alert-text">Please select a valid file type!!!</div>
										</div>
						<?php
									}
								}
							}
						}
						?>

						<div class="wrap-input100 validate-input" data-validate="Your first name is required">
							<input class="input100" type="text" name="userfname" id="userfname" placeholder="First Name" required>
							<span class="focus-input100"></span>
							<span class="symbol-input100">
								<i class="fa fa-user" aria-hidden="true"></i>
							</span>
						</div>
						<div class="wrap-input100 validate-input" data-validate="Valid email is required: ex@abc.xyz">
							<input class="input100" type="text" name="userlname" id="userlname" placeholder="Last Name" required>
							<span class="focus-input100"></span>
							<span class="symbol-input100">
								<i class="fa fa-user" aria-hidden="true"></i>
							</span>
						</div>
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

						<div class="wrap-input100 validate-input" data-validate="Password is required">
							<input class="input100" type="password" name="customercpass" id="customercpass" placeholder="Confirm Password" required>
							<span class="focus-input100"></span>
							<span class="symbol-input100">
								<i class="fa fa-lock" aria-hidden="true"></i>
							</span>
						</div>

						<div class="container-login100-form-btn">
							<button class="login100-form-btn" type="submit">
								Sign In
							</button>
						</div>

						<!-- <div class="text-center p-t-12">
							<span class="txt1">
								Forgot
							</span>
							<a class="txt2" href="#">
								Username / Password?
							</a>
						</div> -->

						<div class="text-center p-t-60">
							<a class="txt2" href="login">
								Already have an account ?
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