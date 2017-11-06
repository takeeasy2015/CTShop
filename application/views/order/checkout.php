<!-- 購物結帳頁 -->
<div class="container main-contant py-5">
	<h1 class="text-center mb-3 text-secondary">訂購結帳</h1>
	<div class="form-row text-center">
		<div class="col-12 col-sm">
			<div class="alert alert-info alert-rounded" role="alert">
				STEP1. 輸入訂單資料
			</div>
		</div>

		<div class="col-12 col-sm">
			<div class="alert alert-secondary alert-rounded" role="alert">
				STEP2. 進行付款
			</div>
		</div>
		<div class="col-12 col-sm">
			<div class="alert alert-secondary alert-rounded" role="alert">
				STEP3. 完成訂購
			</div>
		</div>
	</div>
	
<div class="row justify-content-center mt-5">
	<div class="col-md-8">
		<div class="card">
			<div class="card-header" role="tab" id="headingOrderProductCart">
				<a data-toggle="collapse" href="#orderProductCart" aria-expanded="true" aria-controls="orderProductCart">
					顯示訂購商品
				</a>
			</div>
		</div>
		<div id="orderProductCart" class="collapse show" role="tabpanel" aria-labelledby="headingOrderProductCart" data-parent="#accordion">
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
				<?php if ($cart == null) { ?>
					<tr>
						<td colspan="5" class="align-middle text-center">目前購物車尚無任何商品</td>
					</tr>
				<?php } else { ?>
					<?php foreach ($cart as $key => $value):?>
					<tr id="<?=$value['rowid']; ?>">
					    <td class="align-middle text-center">
							<button class="btn btn-outline-secondary btnOrderItemCartRemove">移除</button>
							<input name="rowid" type="hidden" value="<?=$value['rowid']; ?>">
						</td>
						<td class="align-middle text-center">
							<img style="width:80px; height:80px;" src="//<?=$_SERVER['HTTP_HOST']; ?>/CTShop/<?=$value['options']['image']; ?>" class="#" alt="">
						</td>
						<td class="align-middle text-center"><?=$value['name']; ?></td>
						<td class="align-middle text-center"><?=$value['qty']; ?></td>
						<td class="align-middle text-center"> $<?=$value['price']; ?></td>
					</tr>
					<?php endforeach; ?>
					<tr>
						<td colspan="4" class="align-middle text-center">運費</td>
						<td class="align-middle text-center">
							<strong>$<?=$shippingFee;?></strong>
					</tr>
					<tr>
						<td colspan="4" class="align-middle text-center">合計</td>
						<td class="align-middle text-center cartTotal">
							<strong><?php echo '$' . ($this->cart->total() + $shippingFee); ?></strong>
						</td>
					</tr>
				<?php } ?>
				</tbody>
			</table>
		</div>
		<!-- ----- 訂購人資料 ----- -->
		<h5 class="text-center mt-5">訂購人資料</h5>
		<form action="orderSave" id="orderForm" method="post">
			<div class="form-row">
				<div class="form-group col-md-7">
					<label for="bName">訂購人姓名</label>
					<input type="text" class="form-control" id="bName" name="bName" placeholder="訂購人姓名">
				</div>
				<div class="form-group col-md-5">
					<label for="bIdNumber">身份證字號</label>
					<input type="text" class="form-control" id="bIdNumber" name="bIdNumber" placeholder="身份證字號">
				</div>
				<div class="form-group col-md-6">
					<label for="bEmail">Email</label>
					<input type="email" class="form-control" id="bEmail" name="bEmail" placeholder="service@ct.org.tw">
				</div>
			</div>
			<!-- 聯絡手機 -->
			<label>聯絡手機</label>
			<div class="form-row mb-2">
				<div class="form-group col-sm-3">
					<input type="text" class="form-control" id="bPhoneArea" name="bPhoneArea" maxlength="4" placeholder="">
					<span class="placeholder-block">手機前4碼</span>
				</div>
				
				<div class="form-group col-sm-6">
					<input type="text" class="form-control" id="bPhone" name="bPhone" maxlength="6" placeholder="">
					<span class="placeholder-block">手機後6碼</span>
				</div>
			</div>
			<!-- 聯絡電話 -->
			<label>聯絡電話</label>
			<div class="form-row mb-2">
				<div class="form-group col-sm-3">
					<input type="text" class="form-control" id="bTelArea" name="bTelArea" maxlength="4" placeholder="">
					<span class="placeholder-block">區碼</span>
				</div>
				
				<div class="form-group col-sm-6">
					<input type="text" class="form-control" id="bTel" name="bTel" maxlength="10" placeholder="">
					<span class="placeholder-block">電話號碼</span>
				</div>
				<div class="form-group col-sm-3">
					<input type="text" class="form-control" id="bTelExt" name="bTelExt" maxlength="4" placeholder="">
					<span class="placeholder-block">分機</span>
				</div>
			</div>

			<!-- 聯絡地址 -->
			<div class="form-row">
				<div class="form-group col-md-4">
					<label for="inputCity">縣市</label>
					<select class="form-control" id="bCity" name="bCity">
						<option value="1">台北市</option>
					</select>
				</div>
				<div class="form-group col-md-5">
					<label for="inputCityArea">鄉鎮市區</label>
					<select class="form-control" id="bCityArea" name="bCityArea">
						<option value="100">中正區</option>
						<option value="106">大安區</option>
					</select>
					<input type="hidden" id="bZipCode" name="bZipCode">
				</div>
			</div>
			<div class="form-group">
				<label for="inputAddress">地址</label>
				<input type="text" class="form-control" id="bAddress" name="bAddress" placeholder="幸福路330號">
			</div>
			
			<!-- ----- 收件人資料 ----- -->
			<h5 class="text-center mt-5">收件人資料</h5>
			<div class="form-group">
				<div class="form-check">
					<label class="form-check-label">
						<input type="checkbox" class="form-check-input" name="chooseConInfo"> 同訂購人資料
					</label>
				</div>
			</div>
			
			<div class="form-row">
				<div class="form-group col-md-7">
					<label for="inputName">收件人姓名</label>
					<input type="text" class="form-control" id="cName" name="cName" placeholder="收件人姓名">
				</div>
			</div>

			<!-- 聯絡手機 -->
			<label>聯絡手機</label>
			<div class="form-row mb-2">
				<div class="form-group col-sm-3">
					<input type="text" class="form-control" id="cPhoneArea" name="cPhoneArea" maxlength="4" placeholder="">
					<span class="placeholder-block">手機前4碼</span>
				</div>
				
				<div class="form-group col-sm-6">
					<input type="text" class="form-control" id="cPhone" name="cPhone" maxlength="6" placeholder="">
					<span class="placeholder-block">手機後6碼</span>
				</div>
			</div>

			<!-- 聯絡電話 -->
			<label>聯絡電話</label>
			<div class="form-row mb-2">
				<div class="form-group col-sm-3">
					<input type="text" class="form-control" id="cTelArea" name="cTelArea" maxlength="4" placeholder="">
					<span class="placeholder-block">區碼</span>
				</div>
				
				<div class="form-group col-sm-6">
					<input type="text" class="form-control" id="cTel" name="cTel" maxlength="10" placeholder="">
					<span class="placeholder-block">電話號碼</span>
				</div>
				<div class="form-group col-sm-3">
					<input type="text" class="form-control" id="cTelExt" name="cTelExt" maxlength="4" placeholder="">
					<span class="placeholder-block">分機</span>
				</div>
			</div>

			<!-- 聯絡地址 -->
			<div class="form-row">
				<div class="form-group col-md-4">
					<label for="inputCity">縣市</label>
					<select class="form-control" id="cCity" name="cCity">
						<option value="1">台北市</option>
					</select>
				</div>
				<div class="form-group col-md-5">
					<label for="inputCityArea">鄉鎮市區</label>
					<select class="form-control" id="cCityArea" name="cCityArea">
						<option value="100">中正區</option>
						<option value="106">大安區</option>
					</select>
					<input type="hidden" id="cZipCode" name="cZipCode">
				</div>
			</div>
			<div class="form-group">
				<label for="inputAddress">地址</label>
				<input type="text" class="form-control" id="cAddress" name="cAddress" placeholder="幸福路330號">
			</div>			

			
			<button type="submit" class="btn btn-primary mt-5">前往付款</button>
			<input type="hidden" name="rule" value="ordersave">
		</form> 
		<a href="//<?=$_SERVER['HTTP_HOST']; ?>/CTShop/product/1"><button class="btn btn-outline-secondary mt-5">取消</button></a>
	</div>
</div>



</div>

 <!--  JS Plugin -->
 <script type="text/javascript">
        $(document).ready(function() {
            removeCartItem();
			testInfo(); // test modify
        });

        // function removeCartItem() {
        //     $(".btnOrderItemCartRemove").on('click', function(e) {
        //         e.preventDefault();
        //         var that = $(e.target);
        //         var rowid = that.closest('td').find('input[name=rowid]').val();
        //         console.log('remove rowid: ' + rowid);  // test log
        //         var api = 'http://' + location.host + '/CTShop/api/removeCartItem';
        //         console.log('remove api: ' + api); // test log

        //         $.post(api, {
        //             rowid: rowid
        //         }, function(response) {
        //             console.log('removeCartItem done, res: ' + response); // test log
        //             window.alert(response.rtnMessage);
        //             if (response.rtnCode == 200) {
        //                 location.reload();
        //             }
        //         }, 'json');
        //     });
        // }


		function removeCartItem() {
        	$(".btnOrderItemCartRemove").on('click', function (e) {
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
                                $('#orderProductCart').find('tbody').html('<tr> <td colspan="5" class="align-middle text-center">目前購物車尚無任何商品</td></tr>');
                            } else {
								var totalPrice = response.itemPriceTotal + response.shippingFee;
								$('.cartTotal').html('<strong>$' + total + "</strong>");
							}
                        }
        			},
        			error: function (error) {
                        alert("失敗了!, rowid: " + error.rowid + ", rtnMessage: " + error.rtnMessage); // test alert
        			}
        		});
        	});
        }


		function testInfo() {  // test modify
			$("#bName").val("阿巴");
			$("#bIdNumber").val("A123456789");
			$("#bEmail").val("abc@cc.oo");
			$("#bTelArea").val("02");
			$("#bTel").val("1234567");
			$("#bTelExt").val("101");
			$("#bPhoneArea").val("0911");
			$("#bPhone").val("123456");
			$("#bZipCode").val("110");
			$("#bAddress").val("測試路");

			$("#cName").val("小美");
			$("#cTelArea").val("02");
			$("#cTel").val("1010107");
			$("#cPhoneArea").val("0910");
			$("#cPhone").val("123456");
			$("#cZipCode").val("110");
			$("#cAddress").val("測試路");
		}
		


    </script>
