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
        <button class="btnAddToCart btn btn-primary">加入購物車</button>
        <a href=""><button class="btn btn-danger">商品結帳</button></a>
    </div>
    <hr>

    <div class="container cart">
        <div class="row">
            <div class="column">
                
                <?php if ($cart != null) {
                    
                    foreach ($cart as $key => $value) { ?>  
                    <ul>                  
                        <li>
                            <img style="width:250px; height:250px;" 
                                src="http://localhost:8081/CTShop/<?=$value['options']['image']; ?>" alt="">
                        </li>
                        <li>
                            <?=$value['id']; ?>
                        </li>
                        <li>
                            <?=$value['name']; ?>
                        </li>
                        <li>
                            $<?=$value['price']; ?>
                        </li>
                        <li>
                            <?=$value['qty']; ?>
                        </li>
                        <li>
                            <?=$value['rowid']; ?>
                            <button class="btnItemCartRemove btn btn-secondary">移除商品</button>
                            <input name="rowid" type="hidden" value="<?=$value['rowid']; ?>">
                        </li>
                    </ul>  
                    <?php } ?>
                    <ur><li>$<?=$this->cart->total(); ?></li></ul>
                <?php } else { ?>
                       <ul><li>購物車是空的</li></ul>
                <?php } ?>
                
            
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
            $(".btnItemCartRemove").on('click', function(e) {
                e.preventDefault();
                var that = $(e.target);
                var rowid = that.closest('li').find('input[name=rowid]').val();
                console.log('remove rowid: ' + rowid);  // test log
                var api = 'http://' + location.host + '/CTShop/api/removeCartItem';
                console.log('remove api: ' + api); // test log

                $.post(api, {
                    rowid: rowid
                }, function(response) {
                    console.log('removeCartItem done, res: ' + response); // test log
                    window.alert(response.rtnMessage);
                    if (response.rtnCode == 200) {
                        location.reload();
                    }
                }, 'json');
            });
        }

    </script>
