<?php 

class OrderModel extends CI_Model {

    public function __construct() {
        parent::__construct();

        
    }

    function createOrder($orderDataArray, $orderDetailArray) {
        
        $this->db->trans_begin();
        
        if ($this->db->insert('ct_order', $orderDataArray)) {
            log_message('info', '準備新增orderdetail, orderid:' . $orderDataArray['id']);
            foreach ($orderDetailArray as $key => $value) {
                $this->db->insert('ct_order_detail', $value);
                log_message('info', '新增orderdetail, orderDetailId:' . $value['id']);
            }
        } else {
            log_message('info', '新增訂單失敗, orderid:' . $orderDataArray['id']);
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            log_message('info', '訂單rollback, orderid:' . $orderDataArray['id']);
            return false;
        }
        else {
            $this->db->trans_commit();
            log_message('info', '訂單commit, orderid:' . $orderDataArray['id']);
        }

        return true;
    }
    
    
    function selOrder($orderId) {
        $this->db->where('id', $orderId);
        log_message('debug', '取得訂單, orderId:' . $orderId); // test log
        
        return $this->db->get('ct_order')->row_array();
    }


    function selOrderDetail($orderId) {
        $this->db->where('order_id', $orderId);
        log_message('debug', '取得訂單明細, orderId:' . $orderId); // test log
        
        return $this->db->get('ct_order_detail')->row_array();
    }
}


?>