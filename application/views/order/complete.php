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
         <h4 class="text-center mb-3 text-secondary">購買資訊</h4>
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
                        <tr>
                            <td>欄位1</td>
                            <td>欄位1</td>
                            <td>欄位1</td>
                            <td>欄位1</td>
                            <td>欄位1</td>
                        </tr>
                        <tr>
                            <td>欄位2</td>
                            <td>欄位1</td>
                            <td>欄位1</td>
                            <td>欄位1</td>
                            <td>欄位1</td>
                        </tr>
		    		</tbody>
		    </table>
        </div>
    </div>
    <a class="btn btn-outline-success" href="//<?=$_SERVER['HTTP_HOST']; ?>/CTShop/product/1">回到商品頁</a>
    <?php echo $errorMsg; ?>
</div>