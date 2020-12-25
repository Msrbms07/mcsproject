<?php
include('base/functions.php');
if (!isset($_SESSION['USER_ID_VERIFICATION']) == true) {
    header('refresh:0, url=login');
    die();
}

$order_id = $_GET['order_id'];
$row = load_profile_db();
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <title>Order Details</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <?php include('includes/header.php'); ?>
    <!-- Title Page -->
    <section class="bg-title-page p-t-40 p-b-50 flex-col-c-m" style="background-image: linear-gradient(to bottom, rgba(30,30,30,0.6) 0%,rgba(40,40,40,0.3) 100%), url(images/medic/p54.jpg);">
        <h2 class="l-text2 t-center">
            Order Deatils
        </h2>
    </section>



    <section class="cart bgwhite p-t-70 p-b-100">
        <div class="container">
            <!-- Cart item -->
            <div class="container-table-cart pos-relative">
                <div class="wrap-table-shopping-cart bgwhite">
                    <table class="table-shopping-cart">
                        <tr class="table-head">
                            <th class="column-1"></th>
                            <th class="column-4">Product</th>
                            <th class="column-6">Price</th>
                            <th class="column-7">Quantity</th>
                            <th class="column-8">Total</th>
                        </tr>
                        <?php $order_row = load_order_details($order_id); ?>
                    </table>
                </div>
            </div>

            <div class="flex-w flex-sb-m p-t-25 p-b-25 bo8 p-l-35 p-r-60 p-lr-15-sm">

                <div class="size10 trans-0-4 m-t-10 m-b-10">
                    <!-- Button -->
                    <button onclick="location.href='myorder';" class="flex-c-m sizefull bg1 bo-rad-23 hov1 s-text1 trans-0-4">
                        Back
                    </button>
                </div>
            </div>

            <!-- Total -->
            <div class="bo9 w-size18 p-l-40 p-r-40 p-t-30 p-b-38 m-t-30 m-r-0 m-l-auto p-lr-15-sm">
                <!--  -->
                <div class="flex-w flex-sb-m p-b-20">
                    <span class="m-text22 w-size19 w-full-sm">
                        Total:
                    </span>

                    <span class="m-text21 w-size20 w-full-sm">
                        ₹<?php echo $order_row['order_amount']; ?>
                    </span>
                </div>

                <!--  -->
                <div class="flex-w flex-sb-m bo10 p-t-26 p-b-30">
                    <span class="s-text18 w-size19 w-full-sm">
                        Address:
                    </span>

                    <span class="m-text21 w-size20 w-full-sm">
                        <?php
                            user_address_my_order_details();
                        ?>
                    </span>
                </div>
                <!--  -->
                <div class="flex-w flex-sb-m bo10 p-t-26 p-b-30">
                    <span class="m-text22 w-size19 w-full-sm">
                        Order date:
                    </span>

                    <span class="m-text21 w-size20 w-full-sm">
                        <?php echo date_format_in($order_row['date_c']); ?>
                    </span>
                </div>
                <!--  -->
                <div class="flex-w flex-sb-m p-t-26">
                    <span class="m-text22 w-size19 w-full-sm">
                        Delivery date:
                    </span>

                    <span class="m-text21 w-size20 w-full-sm">
                        <?php echo date_format_in($order_row['order_date']); ?>
                    </span>
                </div>
            </div>
    </section>

    <!-- Cart -->

    <!-- Footer -->
    <?php include('includes/footer.php'); ?>

    <!-- Container Selection -->
    <div id="dropDownSelect1"></div>
    <div id="dropDownSelect2"></div>


    <script>
        function update_cart(qvu) {
            var str = qvu.split("_");
            var prod_quant = str[0];
            var product_id = str[1];

            $.ajax({
                url: "sec_api/update_cart.php",
                method: "post",
                data: {
                    pq: prod_quant,
                    vid: product_id
                },
                success: function(response) {
                    document.getElementById("total_" + product_id).innerHTML = response;
                }
            });

            if (prod_quant == 0) {
                window.location.reload();
            }

            var cart_total = <?php $total = load_cart_total();
                                echo $total ?>;
            document.getElementById("cart_total").innerHTML = "₹" + cart_total;
        }

        function minus(vu) {
            var prod_quant = document.getElementById("prod_quant" + vu).value;
            prod_quant--;
            var str = vu.split("_");
            var product_id = str[1];
            var qvu = prod_quant + vu;
            update_cart(qvu);
        }

        function add(vu) {
            var prod_quant = document.getElementById("prod_quant" + vu).value;
            prod_quant++;
            var str = vu.split("_");
            var product_id = str[1];
            var qvu = prod_quant + vu;
            update_cart(qvu);
        }
    </script>
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