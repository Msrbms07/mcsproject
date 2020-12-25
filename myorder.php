<?php
include('base/functions.php');
if (!isset($_SESSION['USER_ID_VERIFICATION']) == true) {
	header('refresh:0, url=login');
	die();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <style>
        .bs-example {
            margin: 20px;
        }
    </style>
    <title>My Orers | Medicpro</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <?php include('includes/header.php'); ?>
    <!-- Title Page -->
    <section class="bg-title-page p-t-40 p-b-50 flex-col-c-m" style="background-image: linear-gradient(to bottom, rgba(60,60,60,0.6) 0%,rgba(50,50,50,0.6) 100%), url(images/medic/p16.jpg);">
        <h2 class="l-text2 t-center">
            My Orders
        </h2>
    </section>

    <section class="checkout_area padding_top p-t-55 p-b-65">
        <div class="container">
        <?php $uorder_id = user_order_tab(); if ($uorder_id > 0) { ?>
            <div class="row">
                <div class="col-md-2"></div>
                <div class="col-md-8">
                    <table class="table" style="font-size: 18px;">
                        <tbody>
                            <tr>
                                <th>Order ID</th>
                                <th>Date</th>
                                <th>Total</th>
                                <th>Action</th>
                            </tr>
                            <?php load_orders(); ?>
                        </tbody>
                    </table>
                </div>
                <div class="col-md-2"></div>
            </div>
            <?php } else { ?>
                <div class="row">
					<div class="col"></div>
					<div class="size15 trans-0-4 col-6">
						<!-- Button -->
                        <!-- <button type="button" class="btn btn-outline-dark"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> There is no any order has been placed yet ! Order now </button> -->
                        
                        <button class="btn btn-outline-info waves-effect  sizefull"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> There is no any order has been placed yet ! order now</button>
					</div>
					<div class="col"></div>
				</div>
			<?php } ?>
        </div>
    </section>

    <!-- Cart -->

    <!-- Footer -->
    <?php include('includes/footer.php'); ?>

    <!-- Container Selection -->
    <div id="dropDownSelect1"></div>
    <div id="dropDownSelect2"></div>

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

        $(".selection-2").select2({
            minimumResultsForSearch: 20,
            dropdownParent: $('#dropDownSelect2')
        });
    </script>
    <!--===============================================================================================-->
    <script src="js/main.js"></script>

    </body>

</html>