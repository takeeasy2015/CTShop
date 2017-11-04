<?php 
    defined('BASEPATH') OR exit('No direct srcipt access allowed');


class Order extends CI_Controller {

    public function __construct() {
        parent::__construct();

        $this->load->model('OrderModel');
        $this->load->library('ShopConstants');
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
            'title' => 'CTShop - SaveOrder',
            'view' => 'order/forpay.php'
        );

        // 取得購物車
        $cart = $this->cart->contents(true);
        if (sizeof($cart) == 0) {
            $url = 'http://' . $_SERVER['HTTP_HOST'] . '/CTShop/order/checkout';
            log_message('info', 'redirect url: ' . $url); // test log
            redirect($url, 'refresh');

        } else if ($this->input->post('rule') == 'saveorder') {
            $bName =  $this->input->post('bName');
            $bIdNumber = $this->input->post('bIdNumber');
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

           
            $this->OrderModel->createOrder($orderDataArray, $orderDetailArray);
            $this->cart->destroy();

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
    
    function order_detail($orderId, $phone, $email) {    
    }

}

?>