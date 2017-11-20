<?php 
    defined('BASEPATH') OR exit('No direct srcipt access allowed');


class Order extends CI_Controller {

    public function __construct() {
        parent::__construct();

        $this->load->model('OrderModel');
        $this->load->library('ShopConstants');
        include('application/libraries/ECPay.Payment.Integration.php');
        // $this->load->library('email');
    }

    /**
     * Order
     */

    function checkout() {
        $view_data = array(
            'title' => 'CTShop - Checkout',
            'view' => 'order/checkout.php'
        );

        // 取得購物車
        $view_data['cart'] = $this->cart->contents(true);
        $view_data['shippingFee'] = ShopConstants::SHIPPING_FEE;
        $view_data['cartTotal'] = $this->cart->total();

        // 顯示頁面
        $this->load->view("layout", $view_data);
    }

    function singleCheckout() {
        $view_data = array(
            'title' => 'CTShop - singleCheckout',
            'view' => 'order/checkout.php'
        );


        $data = array(
            'num' => $this->input->get('num'),
            'qty' =>  $this->input->get('qty')
        );
        // 呼叫API 加入購物車
        $url = base_url('api/addToCart');
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($curl);
        curl_close($curl);
        
        $response = array();
        if (empty($result)) {
            log_message("debug", "未加入購物車");
        } else {
            $response = json_decode($result, true);  // 讀取json字串, 並轉換成陣列
            if ($response['rtnCode'] == "200") {
                log_message("debug", $response['rtnMessage']);
            } else {
                log_message("debug", $response['rtnMessage']);
            }
        }

        header("Location: ".base_url('checkout'));
    }



        
    function saveOrder() {
        $view_data = array(
            'title' => 'CTShop - Order Save',
            'view' => 'order/forpay.php'
        );

        // 取得購物車
        $cart = $this->cart->contents(true);
        if (sizeof($cart) == 0) {
            $url = base_url('checkout');
            log_message('info', 'redirect url: ' . $url); // test log
            redirect($url, 'refresh');
  
        } else if ($this->input->post('rule') == 'ordersave') {
            $bName =  $this->input->post('bName');
            $bIdNumber = $this->input->post('bIdNumber');
            $bEmail =  $this->input->post('bEmail');
            $bPhoneArea = $this->input->post('bPhoneArea');
            $bPhone = $this->input->post('bPhone');
            $bTelArea = $this->input->post('bTelArea');
            $bTel = $this->input->post('bTel');
            $bTelExt = $this->input->post('bTelExt');
            $bCity = $this->input->post('bCity');
            $bDist = $this->input->post('bDist');
            $bAddress = $this->input->post('bAddress');
            $bZipCode = $this->input->post('bZip');
            log_message('info', 'get info'); // test log
            log_message('info', '訂購人姓名: ' . $bName); // test log
            log_message('info', '訂購人信箱: ' . $bEmail); // test log

            $cName = $this->input->post('cName');
            $cPhoneArea = $this->input->post('cPhoneArea');
            $cPhone = $this->input->post('cPhone');
            $cTelArea = $this->input->post('cTelArea');
            $cTel = $this->input->post('cTel');
            $cTelExt = $this->input->post('cTelExt');
            $cCity = $this->input->post('cCity');
            $cDist = $this->input->post('cDist');
            $cAddress = $this->input->post('cAddress');
            $cZipCode = $this->input->post('cZip');

            $priceTotal = $this->input->post('priceTotal');


            $orderDataArray = array(
                'buyer_name' => $bName,
                'buyer_idcard' => $bIdNumber,
                'buyer_email' => $bEmail,
                'buyer_tel' => $bTelArea . "-" . $bTel . ($bTelExt == null || $bTelExt == '' ? "" : '-' . $bTelExt),
                'buyer_phone' => $bPhoneArea . "-" . $bPhone,
                'buyer_city' => $bCity,
                'buyer_zipcode' => $bZipCode,
                'buyer_addr' => $bDist . $bAddress,
                'buyer_remark' => '',
                'remark' => '',
                'receiver_name' => $cName,
                'receiver_tel' => $cTelArea . "-" . $cTel . ($cTelExt == null || $cTelExt == '' ? "" : '-' . $cTelExt),
                'receiver_phone' => $cPhoneArea . "-" . $cPhone,
                'receiver_city' => $cCity,
                'receiver_zipcode' => $cZipCode,
                'receiver_addr' => $cDist . $cAddress,
                'total_price' => $this->getOrderPriceTotal(),
                'payment' => ShopConstants::OTHER_PAYMENT,
                'create_date' => date('Y-m-d'),
                'create_time' => date('H:i:s'),
            );


            // 訂單明細
            foreach($cart as $key => $value) {
                $orderDetailArray[$key]['product_num'] = $value['id'];
                $orderDetailArray[$key]['product_name'] = $value['name'];
                $orderDetailArray[$key]['product_price'] = $value['price'];
                $orderDetailArray[$key]['product_qty'] = $value['qty'];
                $orderDetailArray[$key]['product_photo'] = $value['options']['image'];
            }

            try {
                // 建立訂單
                $thisOrderId = $this->OrderModel->createOrder($orderDataArray, $orderDetailArray);

                if ( $thisOrderId == "") {
                    log_message("debug", "訂單成立失敗");  // test log
                    throw new IllegalArgumentException("訂單成立失敗");
                }

                $obj = new ECPay_AllInOne();
                $obj->ServiceURL = ShopConstants::SERVICEURL_TEST;
                $obj->HashKey = ShopConstants::HASHKEY_TEST;
                $obj->HashIV = ShopConstants::HASHIV_TEST;
                $obj->MerchantID = ShopConstants::MERCHANTID_TEST;

                $obj->Send['ReturnURL'] =  base_url("payCallback/" . $thisOrderId);
                $obj->Send['OrderResultURL'] = base_url("orderComplete/" . $thisOrderId);
                $obj->Send['MerchantTradeNo'] = $thisOrderId;
                $obj->Send['MerchantTradeDate'] = date('Y/m/d H:i:s');
                $obj->Send['TotalAmount'] = $this->getOrderPriceTotal();  // TODO 這裡要檢查會不會有0元的問題
                $obj->Send['TradeDesc'] = '購買商品';
                $obj->Send['ChoosePayment'] = ECPay_PaymentMethod::Credit;

                log_message('info', 'ReturnURL: ' . $obj->Send['ReturnURL']); // test log
                log_message('info', 'OrderResultURL: ' . $obj->Send['OrderResultURL']); // test log

                // 訂購資料
                foreach ($orderDetailArray as $key => $value) {
                    array_push($obj->Send['Items'], array(
                        'Name' => $value['product_name'],
                        'Price' => $value['product_price'],
                        'Currency' => '元',
                        'Quantity' => $value['product_qty'],
                        'URL' => base_url("orderComplete/" . $thisOrderId)
                    ));
                }

                // 清空購物車
                $this->cart->destroy();

                $obj->CheckOut();
            } catch (IllegalArgumentException $e) {
                $view_data['errorMsg'] = $e->getMessage();
            } catch (Exception $e) {
                echo $e->getMessage();
            }
            
            $view_data['orderDataArray'] = $orderDataArray;

        } else {
            //  (rule != ordersave) , 則顯示errorMsg
            $view_data['errorMsg'] = '系統異常';
        }  

        // 顯示頁面
        $this->load->view("layout", $view_data);
    }
    
    // 付款結果回傳
    function payCallback($orderId) {
        try {
        	$obj = new ECPay_AllInOne();
        	$obj -> HashKey = ShopConstants::HASHKEY_TEST;
        	$obj -> HashIV = ShopConstants::HASHIV_TEST;
        	$obj -> MerchantID = ShopConstants::MERCHANTID_TEST;
        	$response = $obj -> CheckOutFeedback();
            
            log_message('info', 'to CheckOutFeedback'); // test log

            $check = false;
            if ($orderId == $response['MerchantTradeNo']) {
                 $check = true;
            } else {
                log_message('debug', '付款結果回傳的訂單編號不符, orderId: '. $orderId . ', response orderId: ' . $response['MerchantTradeNo']);
            }

            // 檢查是否付款成功
        	if ($check && $response['RtnCode'] == '1') {
                // 檢查未付款的訂單是否存在
                if ($this->OrderModel->checkOrderExist($orderId, ShopConstants::ORDERSTATUS_UNPAID)) { 
                    $updDataArray = array(
                        'status' => ShopConstants::ORDERSTATUS_PAID,
                        'update_date' => date('Y-m-d'),
                        'update_time' => date('H:i:s'),
                    );
                    
                    $updCount = $this->OrderModel->updOrder($orderId, $updDataArray);
                    if ($updCount > 0 ) {
                        log_message('debug', '確認付款成功');
                        echo '1|OK';
                    } else {
                        // 付款失敗
                        log_message('debug', '訂單更新失敗, payment fail.');
                        echo 'FAIL';
                    }
                } else {
                    log_message('debug', '訂單不存在, payment fail.');
                    echo 'FAIL';
                }
        	} else {
                // orderId不匹配; or RtnCode != 1;
                log_message('debug', '付款失敗');
                echo 'FAIL';
        	}

        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }


    // 完成訂單
    function completeOrder($cordId) {
        $view_data = array(
            'title' => 'CTShop - Order Complete',
            'view' => 'order/complete.php'
        );

        $view_data['errorMsg'] = '';

        log_message('info', 'cordId: ' . $cordId); // test log
        if (strlen($cordId) < 10) {
            $view_data['view'] = 'order/fail.php';
            $view_data['errorMsg'] = '找不到訂單編號';
            // 顯示頁面
            $this->load->view("layout", $view_data);
            return;
        }
        
        // 撈出訂單
        $order = $this->OrderModel->selOrder($cordId);

        if (empty($order)) {
            $view_data['view'] = 'order/fail.php';
            $view_data['errorMsg'] = '查無此訂單';
             // 顯示頁面
             $this->load->view("layout", $view_data);
             return;
        } else if (empty($order['orderDetail'])) {
            $view_data['view'] = 'order/fail.php';
            $view_data['errorMsg'] = '查無訂單明細';
             // 顯示頁面
             $this->load->view("layout", $view_data);
             return;
        } else if ($order['status'] != '1') {
            $view_data['view'] = 'order/fail.php';
            $view_data['errorMsg'] = '付款失敗';
             // 顯示頁面
             $this->load->view("layout", $view_data);
             return;
        }


        $view_data['order'] = $order;
        $view_data['shippingFee'] = ShopConstants::SHIPPING_FEE;

        //TODO 寄出訂購成功信, 用try catch包住
        try {
            $ccMail = 'markkao@ct.org.tw';
            if ($this->OrderModel->sendCompleteMail($order['buyer_email'], $ccMail, '', $order)) {
                log_message("debug", "信件已寄出, 收件人: " . $order['buyer_email']);
            } else {
                log_message("debug", "信件寄出失敗, debugger: " . $this->email->print_debugger());
            }
        } catch(Exception $e) {
            log_message("debug", "信件寄出失敗, error: " . $e->getMessage());
        }

        //TODO 寄出廠商通知信, 用try catch包住
      

        $this->load->view("layout", $view_data);
    }

    function test_sendCompleteMail() {   // test modify
        $toMail = 'markkao@ct.org.tw';
        if ($this->OrderModel->sendCompleteMail($toMail, $toMail, '', $order)){
            log_message("debug", "信件已寄出, 收件人: " . $toMail);
            echo '寄信成功<br/>';
            echo $this->email->print_debugger();
        } else {
            echo '寄信失敗<br/>';
            echo $this->email->print_debugger();
        }
        
    }

    function testgenNumber() {
        // $nowTime = time();
        // $fdate = date("ymdHi",time());
        // $squence = 1;
        // $number = 'p' . $fdate . '000' .$squence;

        $codeDate = date('Ymd');
        $codeTime = date('Hi');
        echo $this->OrderModel->genThisOrderId($codeDate, $codeTime);
        



    }

    // 取得訂單總金額(包含運費)
    function getOrderPriceTotal() {
        return (int)$this->cart->total() + ShopConstants::SHIPPING_FEE;
    }
    

    function order_detail($orderId, $phone, $email) {    
    }

    function testresult() {
        $this->db->where('order_id', '5a0567613af5d');
        $orderDetail = $this->db->get('ct_order_detail')->result_array();

        foreach($orderDetail as $key => $value) {
            echo $value['order_id'] . '/';
            echo $value['product_name'] . '/';
            echo $value['product_price'] . '/';
            echo $value['product_qty'] . '/';
            echo '<br/>';
        }
        echo '<br/> 假設: <br/>';
        echo '$orderDetail[0][\'order_id\'] = ' . $orderDetail[0]['order_id'] . '<br/>';
        echo '$orderDetail[0][\'product_name\'] = ' . $orderDetail[0]['product_name'] . '<br/>';
        
        echo '<br/><br/>';
        // 看看陣列中的組成是甚麼
        /* 以下這樣撈會出錯 */
        foreach($orderDetail as $key => $value) {
            echo  $key ;
            echo '<br/>';
            // echo $value;
            // echo '<br/>';
            foreach ($value as $inKey => $inValue) {
                echo 'key: ' . $inKey . ', value: ' . $inValue . '<br/>';
            }
            echo '===========================<br/>';
        }
        /**
         *  結論: 
         * 當我在外迴圈取得的key是第一維陣列index, value表示各筆訂單明細資料的物件.
         * 內迴圈取得的key是訂單明細資料的欄位, value 表示真正的明細內容.
        */
        echo '<hr><br/>';

        $this->db->where('order_id', '5a0567613af5d');
        $orderDetail1 = $this->db->get('ct_order_detail')->row_array();
        echo $orderDetail1['order_id'] . '/';
        echo $orderDetail1['product_name'] . '/';
        echo $orderDetail1['product_price'] . '/';
        echo $orderDetail1['product_qty'] . '/';
        
        echo '<br/><br/>';
        // 看看陣列中的組成是甚麼
        foreach($orderDetail1 as $key => $value) {
            echo 'key: ' . $key . ', value: ' . $value . '<br/>';
        }

        /**
         * 結論: 
         * result_array() 會撈出一個二維陣列數組, 包含每筆資料.
         * row_array() 會撈出一個一維數組, 包含一筆資料.
         *
        */
    }
 }

?>