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
            $url = 'http://' . $_SERVER['HTTP_HOST'] . '/CTShop/checkout';
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
                'buyer_tel' => $bTelArea . "-" . $bTel . ($bTelExt == null || $bTelExt == '' ? "" : $bTelExt),
                'buyer_phone' => $bPhoneArea . "-" . $bPhone,
                'buyer_city' => $bCity,
                'buyer_zipcode' => $bZipCode,
                'buyer_addr' => $bAddress,
                'buyer_remark' => '',
                'remark' => '',
                'receiver_name' => $cName,
                'receiver_tel' => $cTelArea . "-" . $cTel . ($cTelExt == null || $cTelExt == '' ? "" : $cTelExt),
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
                $this->OrderModel->createOrder($orderDataArray, $orderDetailArray);
                $this->cart->destroy();

                $obj = new ECPay_AllInOne();
                
                $obj->ServiceURL = "https://payment-stage.ecpay.com.tw/Cashier/AioCheckOut/V2";
                $obj->HashKey = "5294y06JbISpM5x9";
                $obj->HashIV = "v77hoKGq4kWxNNIS";
                $obj->MerchantID = "2000132";

                $obj->Send['ReturnURL'] = $_SERVER['HTTP_HOST'] . "/CTShop/orderComplete/" . $orderDataArray['id'];
                $obj->Send['MerchantTradeNo'] = $orderDataArray['id'];
                $obj->Send['MerchantTradeDate'] = date('Y/m/d H:i:s');
                $obj->Send['TotalAmount'] = $this->cart->total() + ShopConstants::SHIPPING_FEE;
                $obj->Send['TradeDesc'] = '購買商品';
                $obj->Send['ChoosePayment'] = ECPay_PaymentMethod::Credit;

                //訂購資料
                foreach ($orderDetailArray as $key => $value) {
                    array_push($obj->Send['Items'], array(
                        'Name' => $value['product_name'],
                        'Price' => $value['product_price'],
                        'Currency' => '元',
                        'Quantity' => $value['product_qty'],
                        'URL' => $_SERVER['HTTP_HOST'] . "/CTShop/orderComplete/" . orderDataArray['id']
                    ));
                }

                $obj->CheckOut();

            } catch (Exception $e) {
                echo $e->getMessage();
            }
            
            $view_data['orderDataArray'] = $orderDataArray;

        } else {
            $view_data['errorMsg'] = '系統異常';
        }  

        // 顯示頁面
         $this->load->view("layout", $view_data);
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
        return $this->cart->total() + ShopConstants::SHIPPING_FEE;
    }
    
    function completeOrder($cordId) {
        $view_data = array(
            'title' => 'CTShop - Order Complete',
            'view' => 'order/complete.php'
        );

        log_message('info', 'cordId: ' . $cordId); // test log
        if (sizeof($cordId) < 14) {
            $view_data['errorMsg'] = '找不到訂單編號';
            // 顯示頁面
            $this->load->view("layout", $view_data);
            return;
        }

        $order = $this->OrderModel->selOrder((string) $cordId);
        $orderDetail = $this->OrderModel->selOrderDetail((string) $cordId);



        $view_data['errorMsg'] = '有訂單';

        $this->load->view("layout", $view_data);
    }

    function order_detail($orderId, $phone, $email) {    
    }

    
}

?>