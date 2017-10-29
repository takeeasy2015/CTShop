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
    <script type="text/javascript" src="http://localhost:8081/CTShop/assets/js/vendor/jquery-2.1.1.min.js" />
	<script type="text/javascript" src="http://localhost:8081/CTShop/assets/js/vendor/jquery-ui-1.9.2.custom.min.js"></script>
	<script type="text/javascript" src="http://localhost:8081/CTShop/assets/js/vendor/modernizr-2.6.2.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"></script>
    <script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js"></script>

  </head>
  <body>
    <div class="productInfo">
        <ul>
            <li>
                <img style="width:250px; height:250px;" 
                    src="http://localhost:8081/CTShop/<?=$product['main_photo_path']?>" alt="">
            </li>
            <li>
                商品編號：<?=$product['num'];?>
                <input name="productNum" type="hidden" value="<?=$product['num'];?>">
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
                    $<?=$product['original_price'];?> 
                </span>
            </il>
            <li>
                商品特惠價：$<?=$product['price'];?>
            </il>
            <li>選擇數量: 
                <select name="needQty">
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</opiton>
                </select>
            </li>
        </ul>
        <button class="btnAddToCart">加入購物車</button>
        <a href=""><button>商品結帳</button></a>
    </div>
    

    <!--  JS Plugin -->
    <script type="text/javascript">
        $(document).ready(function() {
            addToCart();
        });
        
        
        function addToCart() {
            $('.btnAddToCart').on('click', function(e) {
                e.preventDefault();  // 先放預設清除
                var that = $(this);
                var id = that.closest('.productInfo').find("input[name=productNum]").val();
                var qty = that.closest('.productInfo').find('select[name=needQty]').val();
                console.log('id: ' + id + ', qty: ' + qty); // test log
                var api = 'api/addToCart';

                $.post(api, {
                    id: id,
                    qty: qty
                }, function(response) {
                    window.alert(response.sys_msg);
                    if (response.sys_code == 200) {
                        location.reload();
                    }
                })
            })


        }
    </script>

  </body>
</html>