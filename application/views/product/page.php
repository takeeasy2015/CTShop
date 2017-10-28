<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css">

	<!--  JS Important  -->

	<!-- [if lt IE 9]>
	<script type="text/javascript" src="assets/js/vendorjquery-1.11.0.min.js" />
	<! [endif] -->
    <script type="text/javascript" src="assets/js/vendor/jquery-2.1.1.min.js" />
	<script type="text/javascript" src="assets/js/vendor/jquery-ui-1.9.2.custom.min.js"></script>
	<script type="text/javascript" src="assets/js/vendor/modernizr-2.6.2.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"></script>
    <script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js"></script>

  </head>
  <body>
    <ul>
        <li>
            商品編號：<?=$product['num'];?>
        </il>
        <li>
            商品名稱：<?=$product['title'];?>
        </il>
        <li>
            商品副標：<?=$product['sub_title'];?>
        </il>
        <li>
            商品原價： 
            <span style="text-decoration:line-through;"> 
                <?=$product['original_price'];?> 
            </span>
        </il>
        <li>
            商品特惠價：<?=$product['price'];?>
        </il>
    </ul>

    <button onclick="">前往訂購</button>


    <!--  JS Plugin -->
   
  </body>
</html>