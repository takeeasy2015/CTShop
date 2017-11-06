<?php 
defined('BASEPATH') OR exit('No direct srcipt access allowed');

class CartApi extends CI_Controller {
    
    public function __construct() {
        parent::__construct();

        $this->load->model('ProductModel');

        $this->load->library('ShopConstants');
    }


    /**
     * Cart
     */

     function addToCart() {
         $productNum = $this->input->post('num');
         $qty = $this->input->post('qty');

         if ( !$this->ProductModel->checkExist($productNum) ) {
            $dataResponse['rtnCode'] = 404;
            $dataResponse['rtnMessage'] = '商品不存在';
            log_message('info', '商品不存在'); // test log
         } else if ( !$this->ProductModel->checkStock($productNum, $qty) ) {
            $dataResponse['rtnCode'] = 500;
            $dataResponse['rtnMessage'] = '商品目前已無庫存';
            log_message('info', '商品無庫存'); // test log
         } else {
             $product = $this->ProductModel->selProduct($productNum);
             
             log_message('info', 
                'productName: ' . $product['title'] . 
                ', productNum: ' . $productNum .
                ', productQty: ' . $qty . 
                ', productPrice: ' . $product['price']); // test log

             $data = array(
                 'id'=> $productNum,
                 'qty'=> $qty,
                 'price'=> $product['price'],
                 'name'=> $product['title'],
                 'options' => array(
                     'image' => $product['main_photo_path']
                 )
             );

             log_message('info', '商品準備加入到購物車'); // test log
             $this->cart->insert($data);
             log_message('info', '商品已加入購物車'); // test log

             $dataResponse['rtnCode'] = 200;
             $dataResponse['rtnMessage'] = '商品成功加入購物車';

             $this->checkCart();  // test modify
         }

         echo json_encode($dataResponse);
     }


     function removeCartItem() {
        $rowid = $this->input->post('rowid');
        
        if ($rowid != null && $this->cart->remove($rowid)) {
            $dataResponse['rtnCode'] = 200;
            $dataResponse['rtnMessage'] = '商品移除成功';
            $dataResponse['rowid'] = $rowid;
        } else {
            $dataResponse['rtnCode'] = 404;
            $dataResponse['rtnMessage'] = '商品移除失敗';
            log_message('info', '商品移除失敗'); // test log
        }
        
        $dataResponse['itemSize'] = $this->cart->total_items();
        $dataResponse['itemPriceTotal'] = $this->cart->total(); // test modify 加上運費
        $dataResponse['shippingFee'] = ShopConstants::SHIPPING_FEE;
        echo json_encode($dataResponse);
     }


     function checkCart() {  // test function
        $view_data['cart'] = $this->cart->contents(true);
        
                if ($view_data['cart'] == null) {
                    log_message('info', 'cart is empty'); // test log
                } else {
                    log_message('info', 'cart is not empty'); // test log
                    
                    foreach ($view_data['cart'] as $key => $value) {
                        log_message('debug', $value['options']['image']);
                        log_message('debug', $value['id']);
                        log_message('debug', $value['name']);
                        log_message('debug', $value['price']);
                        log_message('debug', $value['qty']);
                        log_message('debug', $value['rowid']);
                        log_message('debug', $value['subtotal']);
                    }
                }
     }

}

?>