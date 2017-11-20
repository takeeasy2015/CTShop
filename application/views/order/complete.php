<!-- 購物結帳頁 -->
<div class="container main-contant py-5">
	<h1 class="text-center mb-3 text-secondary">感謝您的購買</h1>
    <section>
    <div class="form-row text-center">
		<div class="col-12 col-sm">
			<div class="alert alert-info alert-rounded" role="alert">
			    完成訂購
			</div>
		</div>
	</div>
    </section>

    <div class="row justify-content-center mt-5">
         <div class="col-md-8">
         <h4 class="text-center mb-3 text-secondary">訂單資訊</h4>
		 <h5 class="text-center mb-3 text-secondary"><?=$order['id'];?></h5>
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
            	<?php if ($order == null) { ?>
            	<tr>
            		<td colspan="5" class="align-middle text-center">訂單異常</td>
            	</tr>
            	<?php } else { ?>
            	<?php foreach ($order['orderDetail'] as $key => $value):?>
            	<tr id="<?=$value['id']; ?>">
            		<td class="align-middle text-center">
            		</td>
            		<td class="align-middle text-center">
            			<?=$value['product_name']; ?>
            		</td>
            		<td class="align-middle text-center">
            			<?=$value['product_qty']; ?>
            		</td>
            		<td class="align-middle text-center"> $
            			<?=$value['product_price']; ?>
            		</td>
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
            			<strong>
            				<?php echo '$' . ($order['total'] + $shippingFee); ?>
            			</strong>
						<strong style="display:none"><?php echo '$' . ($order['total_price']); ?></strong>
            		</td>
            	</tr>
            	<?php } ?>
            </tbody>
        </table>
    </div>
	<section>
	<div>
    	<a class="btn btn-outline-success" href="//<?=$_SERVER['HTTP_HOST']; ?>/CTShop/product/1">回到商品頁</a>
    	<?php echo $errorMsg; ?>
	</div>
	</section>
</div>