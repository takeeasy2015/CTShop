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
		<!-- ----- 訂購人資訊 ----- -->
		<h5 class="text-center mt-5">訂購人資訊</h5>
		<form action="orderSave" name="orderForm" method="post">
			<div class="form-row">
				<div class="form-group col-md-7">
					<label for="bName">訂購人姓名</label>
					<input type="text" class="form-control" id="bName" name="bName" placeholder="請輸入真實姓名">
				</div>
				<div class="form-group col-md-5">
					<label for="bIdNumber">身份證字號</label>
					<input type="text" class="form-control" id="bIdNumber" name="bIdNumber" placeholder="身份證字號">
				</div>
				<div class="form-group col-md-6">
					<label for="bEmail">Email</label>
					<input type="email" class="form-control" id="bEmail" name="bEmail" placeholder="請填寫正確且慣用的電子信箱 e.g. service@ct.org.tw">
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
					<input type="text" class="form-control" id="bTelArea" name="bTelArea" maxlength="2" placeholder="">
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
			<label for="inputAddress">地址</label>
			<div class="form-row">
				<div class="form-group col-sm-3">
					<input type="text" class="form-control" id="bZipCode" name="bZipCode" placeholder="郵遞區號" readonly>
				</div>
				<div class="form-group col-sm-4">
					<select class="form-control" id="bCity" name="bCity">
						<option value="縣市">縣市</option>
					</select>
				</div>
				<div class="form-group col-sm-5">
					<select class="form-control" id="bDistrict" name="bDistrict">
						<option value="鄉鎮市區">鄉鎮市區</option>
					</select>
				</div>
			</div>
			<div class="form-group">
				<input type="text" class="form-control" id="bAddress" name="bAddress" placeholder="詳細地址">
			</div>
			<input type="hidden" name="bDist">
			<input type="hidden" name="bZip">
			
			<!-- ----- 收件人資訊 ----- -->
			<h5 class="text-center mt-5">收件人資訊</h5>
			<div class="form-group">
				<div class="form-check">
					<label class="form-check-label">
						<input type="checkbox" class="form-check-input" name="buyerIsReceiver"> 同訂購人資料
					</label>
				</div>
			</div>

			<div class="receiverBlock">
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
						<input type="text" class="form-control" id="cTelArea" name="cTelArea" maxlength="2" placeholder="">
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
				<label for="inputAddress">地址</label>
				<div class="form-row">
					<div class="form-group col-sm-3">
						<input type="text" class="form-control" id="cZipCode" name="cZipCode" placeholder="郵遞區號" readonly>
					</div>
					<div class="form-group col-sm-4">
						<select class="form-control" id="cCity" name="cCity">
							<option value="縣市">縣市</option>
						</select>
					</div>
					<div class="form-group col-sm-5">
						<select class="form-control" id="cDistrict" name="cDistrict">
							<option value="鄉鎮市區">鄉鎮市區</option>
						</select>
					</div>
				</div>
				<div class="form-group">
					<input type="text" class="form-control" id="cAddress" name="cAddress" placeholder="詳細地址">
				</div>
				<input type="hidden" name="cDist">
				<input type="hidden" name="cZip">
			</div>
			
			<button type="submit" class="btn btn-primary mt-5">前往付款</button>
			<a href="//<?=$_SERVER['HTTP_HOST']; ?>/CTShop/product/1"><button class="btn btn-outline-secondary mt-5">取消</button></a><!-- TODO 要再更換網址 -->
			<input type="hidden" name="rule" value="ordersave">
		</form> 
	</div>
</div>



</div>

 <!--  JS Plugin -->
<script type="text/javascript" src="<?=base_url('assets/js/address.js?v11110122')?>"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/jquery.validate.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/additional-methods.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/localization/messages_zh_TW.min.js"></script>

 <script type="text/javascript">
        $(document).ready(function() {
            removeCartItem();
			testInfo(); // test modify
			checkBuyerIsReceiver();
			validateForm();
			initLocationOption();
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
			$("#hbZipCode").val("110");
			$("#hbCity").val($("#bCity :selected").val());
			$("#hbDist").val($("#bDistrict :selected").val());
			$("#bAddress").val("測試路50號");
			

			$("#cName").val("小美");
			$("#cTelArea").val("02");
			$("#cTel").val("1010107");
			$("#cTelExt").val("");
			$("#cPhoneArea").val("0910");
			$("#cPhone").val("123456");
			$("#hcZipCode").val("110");
			$("#hcCity").val($("#cCity :selected").val());
			$("#hcDist").val($("#cDistrict :selected").val());
			$("#cAddress").val("測試路");
		}
		

		function checkBuyerIsReceiver() {
			$('input[name=buyerIsReceiver]').change(function(){
				if ($(this).prop("checked")) {
					$("#cName").val($("#bName").val());
					$("#cTelArea").val($("#bTelArea").val());
					$("#cTel").val($("#bTel").val());
					$("#cTelExt").val($("#bTelExt").val());
					$("#cPhoneArea").val($("#bPhoneArea").val());
					$("#cPhone").val($("#bPhone").val());
					$("#cZipCode").val($("#bZipCode").val());
					$("#cCity").val($("#bCity").val());
					$("#cDist").val($("#bDist").val());
					$("#cAddress").val($("#bAddress").val());
					$('.receiverBlock').css('display', 'none');
				} else {
					$('.receiverBlock').css('display', 'inline');
				}

			});
		}


		function validateForm() {
			$('form[name=orderForm]').validate({
				errorClass: "order-input-error",
				// validClass: "order-input-valid",
				rules: {
					bName: {
						required: true,
						minlength: 2
					},
					cName: {
						required: true,
						minlength: 2
					},
					bIdNumber: {
						required: true,
						minlength: 10
					},
					bEmail: {
						required: true,
						email: true
					},
					bTelArea: {
						number: true,
						maxlength: 4
					},
					cTelArea: {
						number: true,
						maxlength: 4

					},
					bTel: {
						number: true,
						maxlength: 10
					}, 
					cTel: {
						number: true,
						maxlength: 10
					}, 
					bTelExt: {
						number: true,
						maxlength: 4
					},
					cTelExt: {
						number: true,
						maxlength: 4
					}, 
					bPhoneArea: {
						required: true,
						number: true,
						minlength: 4,
						maxlength: 4,
					}, 
					cPhoneArea: {
						required: true,
						number: true,
						minlength: 4,
						maxlength: 4
					},
					bPhone: {
						required: true,
						number: true,
						minlength: 6,
						maxlength: 10
						
					},
					cPhone: {
						required: true,
						number: true,
						minlength: 6,
						maxlength: 10
					},
					bAddress: {
						required: true,
						minlength:5
					},
					cAddress: {
						required: true,
						minlength:5
					}
				},
				messages: {
					bName: {
						required: "請輸入姓名",
						minlength: "姓名至少為2個字母組成"
					},
					cName: {
						required: "請輸入姓名",
						minlength: "姓名至少為2個字母組成"
					},
					bIdNumber: {
						required: "請輸入身份證字號",
						minlength: "身分證字號長度不可小於10個字母"
					},
					bEmail: {
						required: "請輸入電子郵件信箱",
						email: "請輸入一個有效的電子郵件信箱"
					},
					bTelArea: {
						number: "電話區碼必須為數字",
						maxlength: "區碼至多為2碼"
					},
					cTelArea: {
						number: "電話號碼必須為數字",
						maxlength: "區碼至多為2碼"

					},
					bTel: {
						number: "電話號碼必須為數字",
						maxlength: "號碼至多為10碼"
					}, 
					cTel: {
						number: "電話號碼必須為數字",
						maxlength: "號碼至多為10碼"
					}, 
					bTelExt: {
						number: "分機號碼必須為數字",
						maxlength: "號碼至多為4碼"
					},
					cTelExt: {
						number: "分機號碼必須為數字",
						maxlength: "號碼至多為4碼"
					}, 
					bPhoneArea: {
						required: "請輸入手機前四碼",
						number: "手機前四碼必須為數字",
						minlength: "號碼至少為4碼", 
						maxlength: "號碼至多為4碼"
					}, 
					cPhoneArea: {
						required: "請輸入手機前四碼",
						number: "手機前四碼必須為數字",
						minlength: "號碼至少為4碼", 
						maxlength: "號碼至多為4碼"
					},
					bPhone: {
						required: "請輸入手機後六碼",
						number: "手機後六碼必須為數字",
						minlength: "號碼至少為6碼", 
						maxlength: "號碼至少為6碼"
					},
					cPhone: {
						required: "請輸入手機後六碼",
						number: "手機後六碼必須為數字",
						minlength: "號碼至少為6碼", 
						maxlength: "號碼至少為6碼"
					},
					bAddress: {
						required: "請輸入訂購人地址",
						minlength: "請輸入有效的地址"
					},
					cAddress: {
						required: "請輸入收件人地址",
						minlength: "請輸入有效的地址"
					}
				}
			})
		}
		
		function initLocationOption() {
			var bCity = document.getElementById('bCity');
			var bDistrict = document.getElementById('bDistrict');
			initCityAndDist(document.orderForm.bZipCode.value, bCity, bDistrict);

			var cCity = document.getElementById('cCity');
			var cDistrict = document.getElementById('cDistrict');
			initCityAndDist(document.orderForm.cZipCode.value, cCity, cDistrict);

			$("#bCity").on('change', function () {
				showDistGroup(bDistrict, bCity.value, '');
				changeZip(document.orderForm.bZip, document.orderForm.bDist, bDistrict);
				$("#bZipCode").val($('input[name=bZip]').val());
			});

			$("#bDistrict").on('change', function(){
				changeZip(document.orderForm.bZip, document.orderForm.bDist, bDistrict);
				$("#bZipCode").val($('input[name=bZip]').val());
			});

			$("#cCity").on('change', function () {
				showDistGroup(cDistrict, cCity.value, '');
				changeZip(document.orderForm.bZip, document.orderForm.bDist, bDistrict);
				$("#bZipCode").val($('input[name=bZip]').val());
			});

			$("#cDistrict").on('change', function(){
				changeZip(document.orderForm.cZip, document.orderForm.cDist, cDistrict);
				$("#cZipCode").val($('input[name=cZip]').val());
				

			});
		}

</script>