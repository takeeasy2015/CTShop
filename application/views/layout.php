<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title><?=$title;?></title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css">

    <!--  JS Important  -->

    <!-- [if lt IE 9]>
    <script type="text/javascript" src="assets/js/vendorjquery-1.11.0.min.js" />
    <! [endif] -->
    <script type="text/javascript" src="http://localhost:8081/CTShop/assets/js/vendor/jquery-2.1.1.min.js" />
    <script type="text/javascript" src="http://localhost:8081/CTShop/assets/js/vendor/jquery-ui-1.9.2.custom.min.js"></script>
    <script type="text/javascript" src="http://localhost:8081/CTShop/assets/js/vendor/modernizr-2.6.2.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"></script>
    <script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js"></script>

</head>

<body>
    <div class="container">
        <?php include('header.php'); ?>
        <?php include($view); ?>
        <?php include('footer.php')?>
    </div>

     <!--  JS Plugin -->
     <script type="text/javascript">
        $(document).ready(function() {
            
        });
    </script>
</body>

</html>
