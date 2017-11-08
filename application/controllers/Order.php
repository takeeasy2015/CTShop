<?php 
    defined('BASEPATH') OR exit('No direct srcipt access allowed');


class Order extends CI_Controller {

    public function __construct() {
        parent::__construct();

        $this->load->model('OrderModel');
        $this->load->library('ShopConstants');
        include('application/libraries/ECPay.Payment.Integration.php');
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



        // 顯示頁面
        $this->load->view("layout", $view_data);
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
            $bCityArea = $this->input->post('bCityArea');
            $bAddress = $this->input->post('bAddress');
            $bZipCode = $this->input->post('bZipCode');
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
            $cCityArea = $this->input->post('cCityArea');
            $cAddress = $this->input->post('cAddress');
            $cZipCode = $this->input->post('cZipCode');

            $priceTotal = $this->input->post('priceTotal');


            $orderDataArray = array(
                'buyer_name' => $bName,
                'buyer_idcard' => $bIdNumber,
                'buyer_email' => $bEmail,
                'buyer_tel' => $bTelArea . "-" . $bTel . ($bTelExt == null || $bTelExt == '' ? "" : '-' . $bTelExt),
                'buyer_phone' => $bPhoneArea . "-" . $bPhone,
                'buyer_city' => $bCity,
                'buyer_zipcode' => $bZipCode,
                'buyer_addr' => $bAddress,
                'buyer_remark' => '',
                'remark' => '',
                'receiver_name' => $cName,
                'receiver_tel' => $cTelArea . "-" . $cTel . ($cTelExt == null || $cTelExt == '' ? "" : '-' . $cTelExt),
                'receiver_phone' => $cPhoneArea . "-" . $cPhone,
                'receiver_city' => $bCity,
                'receiver_zipcode' => $bZipCode,
                'receiver_addr' => $bAddress,
                'total_price' => $this->getOrderPriceTotal(),
                'payment' => ShopConstants::OTHER_PAYMENT,
                'id' => uniqid(),
                'create_date' => date('Y-m-d'),
                'create_time' => date('H:i:s'),
            );

            $orderDataArray['id_uni'] = sha1($orderDataArray['id']);


            // 訂單明細
            foreach($cart as $key => $value) {
                $orderDetailArray[$key]['id'] = uniqid();
                $orderDetailArray[$key]['order_id'] = $orderDataArray['id'];
                $orderDetailArray[$key]['product_num'] = $value['id'];
                $orderDetailArray[$key]['product_name'] = $value['name'];
                $orderDetailArray[$key]['product_price'] = $value['price'];
                $orderDetailArray[$key]['product_qty'] = $value['qty'];
                $orderDetailArray[$key]['product_photo'] = $value['options']['image'];
            }

            try {
                // 建立訂單
                $this->OrderModel->createOrder($orderDataArray, $orderDetailArray);

                $obj = new ECPay_AllInOne();
                
                $obj->ServiceURL = ShopConstants::SERVICEURL_TEST;
                $obj->HashKey = ShopConstants::HASHKEY_TEST;
                $obj->HashIV = ShopConstants::HASHIV_TEST;
                $obj->MerchantID = ShopConstants::MERCHANTID_TEST;

                $obj->Send['ReturnURL'] =  base_url("payCallback/" . $orderDataArray['id']);
                $obj->Send['OrderResultURL'] = base_url("orderComplete/" . $orderDataArray['id']);
                $obj->Send['MerchantTradeNo'] = $orderDataArray['id'];
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
                        'URL' => base_url("orderComplete/" . $orderDataArray['id'])
                    ));
                }

                // 清空購物車
                $this->cart->destroy();

                $obj->CheckOut();

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
                log_message('info', '付款結果回傳的訂單編號不符, orderId: '. $orderId . ', response orderId: ' . $response['MerchantTradeNo']);
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
                        echo '1|OK';
                    } else {
                        // 付款失敗
                        echo 'FAIL';
                    }
                }
        	} else {
                // orderId不匹配; or RtnCode != 1;
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

        log_message('info', 'cordId: ' . $cordId); // test log
        if (strlen($cordId) < 10) {
            $view_data['view'] = 'order/fail.php';
            $view_data['errorMsg'] = '找不到訂單編號';
            // 顯示頁面
            $this->load->view("layout", $view_data);
            return;
        }
        
        // 撈出訂單
        $view_data['order'] = $this->OrderModel->selOrder($cordId);


        $view_data['errorMsg'] = '有訂單';

        $this->load->view("layout", $view_data);
        $order = $this->OrderModel->selOrder((string) $cordId);
        $orderDetail = $this->OrderModel->selOrderDetail($order);
        
    }


    function testgenNumber() {
        $nowTime = time();
        $fdate = date("ymdHi",time());
        $squence = 1;
        $number = 'p' . $fdate . '000' .$squence;

        echo $number;

    }

    // 取得訂單總金額(包含運費)
    function getOrderPriceTotal() {
        return (int)$this->cart->total() + ShopConstants::SHIPPING_FEE;
    }
    

    function order_detail($orderId, $phone, $email) {    
    }

    
}

?>