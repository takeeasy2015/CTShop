<?php 
    defined('BASEPATH') OR exit('No direct srcipt access allowed');


class Order extends CI_Controller {

    public function __construct() {
        parent::__construct();

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



        // 顯示頁面
        $this->load->view("layout", $view_data);
    }


    function order_detail($orderId, $phone, $email) {
        
        
    }


}

?>