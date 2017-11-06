 <!-- 測試 -->
 <?php

    echo '接著前往付款頁面<br/>';
    echo '<hr> <br/> <h3>以下是測試頁區塊</h3> <br/><br/>';

    if (sizeof($orderDataArray) > 0) {
        foreach($orderDataArray as $key => $value) {
            echo "Key: $key; Value: $value<br />\n";   // test modify
        }
    }
?>

<a class="btn btn-outline-success" href="//<?=$_SERVER['HTTP_HOST']; ?>/CTShop/product/1">回到商品頁</a>

<a class="btn btn-outline-success" href="//<?=$_SERVER['HTTP_HOST']; ?>/CTShop/orderComplete/<?=$orderDataArray['id'] ?>">完成訂購</a>