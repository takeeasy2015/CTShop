    <div class="productInfo">
        <ul>
            <li>
                <img style="width:250px; height:250px;" 
                    src="//<?=$_SERVER['HTTP_HOST']; ?>/CTShop/<?=$product['main_photo_path']?>" alt="">
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
        <button class="btnAddToCart btn btn-primary">加入購物車</button>
        <a href="//<?=$_SERVER['HTTP_HOST']; ?>/CTShop/order/checkout"><button class="btn btn-danger">商品結帳</button></a>
    </div>
    <hr>

    <div class="container cart">
        <div class="row">
            <div class="column">
            <div id="productCart" class="collapse show" role="tabpanel" aria-labelledby="headingOne" data-parent="#accordion">
			<table class="table table-sm">
				<thead>
				      <tr>
					  	<th width="30"></th>
                		<th width="100"></th>
						<th class="text-center">商品名稱</th>
						<th width="100">數量</th>
                						<th width="80">小計</th>
                					</tr>
                				</thead>
                				<tbody>
                <?php if (sizeof($cart) == 0) { ?>
                <tr>
                	<td colspan="5" class="align-middle text-center">目前購物車尚無任何商品</td>
                </tr>
                <?php } else { ?>
                    <?php foreach ($cart as $key => $value):?>
                <tr id="<?=$value['rowid']; ?>">
                	<td class="align-middle text-center">
                		<button class="btn btn-outline-secondary btnItemCartRemove">移除</button>
                		<input name="rowid" type="hidden" value="<?=$value['rowid']; ?>">
                		<div class="productId">商品編號：
                			<?=$value['id']; ?>
                		</div>
                	</td>
                	<td class="align-middle text-center">
                		<img style="width:80px; height:80px;" src="//<?=$_SERVER['HTTP_HOST']; ?>/CTShop/<?=$value['options']['image']; ?>" class="#"
                		alt="">
                	</td>
                	<td class="align-middle text-center">
                		<?=$value['name']; ?>
                	</td>
                	<td class="align-middle text-center">
                		<?=$value['qty']; ?>
                	</td>
                	<td class="align-middle text-center"> $
                		<?=$value['price']; ?>
                	</td>
                </tr>
                <?php endforeach; ?>
                <tr>
                	<td colspan="4" class="align-middle text-center">合計</td>
                	<td class="align-middle text-center productTotal">
                		<strong>
                			<?php echo '$' . $this->cart->total(); ?>
                		</strong>
                	</td>
                </tr>
                <?php } ?>
                </tbody>
                </table>
                </div>

            
            </div>
        </div>
    </div>

    <!--  JS Plugin -->
    <script type="text/javascript">
        $(document).ready(function() {
            addToCart();
            removeCartItem();
        });
        
        
        function addToCart() {
            $('.btnAddToCart').on('click', function(e) {
                e.preventDefault();  // 先放預設清除
                var that = $(e.target);
                var num = that.closest('.productInfo').find('input[name=productNum]').val();
                var qty = that.closest('.productInfo').find('select[name=needQty]').val();
                console.log('num: ' + num + ', qty: ' + qty); // test log
                console.log(location.host); // test log
                var api = 'http://' + location.host + '/CTShop/api/addToCart';

                console.log('api:' + api);

                $.post(api, {
                    num: num,
                    qty: qty
                }, function(response) {
                    console.log('addToCart done: ' + response); // test log
                    window.alert(response.rtnMessage);
                    if (response.rtnCode == 200) {
                        location.reload();
                    }
                }, 'json');
            });
        }

        function removeCartItem() {
        	$(".btnItemCartRemove").on('click', function (e) {
        		e.preventDefault();
        		var that = $(e.target);
        		var rowid = that.closest('tr').find('input[name=rowid]').val();
        		console.log('remove rowid: ' + rowid); // test log
        		var api = 'http://' + location.host + '/CTShop/api/removeCartItem';
        		console.log('remove api: ' + api); // test 

        		$.ajax({
        			url: 'http://' + location.host + '/CTShop/api/removeCartItem',
        			type: "POST",
        			cache: false,
                    data: {rowid: rowid},
                    dataType: 'json',
        			success: function (response) {
                        console.log(response); // test log
                        window.alert(response.rtnMessage);
                        if (response.rtnCode == 200) {
                            // location.reload();
                            var item = 'tr#'+ response.rowid;
                            console.log($(item)); // test log
                            $(item).remove();
                            if (response.itemSize < 1) {
                                $('#productCart').find('tbody').html('<tr> <td colspan="5" class="align-middle text-center">目前購物車尚無任何商品</td></tr>');
                            } else {
                                $('.productTotal').html('<strong>$' + (response.itemPriceTotal + response.shippingFee) + "</strong>");
                            }
                        }
        			},
        			error: function (error) {
                        alert("失敗了!, rowid: " + error.rowid + ", rtnMessage: " + error.rtnMessage); // test alert
        			}
        		});
        	});
        }

    </script>
