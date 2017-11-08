<?php 

class OrderModel extends CI_Model {

    public function __construct() {
        parent::__construct();

        
    }

    function createOrder($orderDataArray, $orderDetailArray) {
        
        $this->db->trans_begin();
        
        if ($this->insOrder($orderDataArray) < 0) {
            log_message('info', '新增訂單失敗, orderid:' . $orderDataArray['id']);
            $this->db->trans_rollback();
            return false;
        }
        
        log_message('info', '準備新增orderdetail, orderid:' . $orderDataArray['id']);
        
        if ($this->insOrderDetail($orderDetailArray) < 0) {
            log_message('info', '新增訂單明細失敗, orderid:' . $orderDetailArray['order_id']['id']);
            $this->db->trans_rollback();
            return false;
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            log_message('info', '訂單rollback, orderid:' . $orderDataArray['id']);
            return false;
        } else {
            $this->db->trans_commit();
            log_message('info', '訂單commit, orderid:' . $orderDataArray['id']);
        }

        return true;
    }

    
    function selUserOrder($orderId, $buyerPhone) {
        $conditions = "order_id='$orderId' AND buyer_phone='$buyerPhone'";
        $this->db->where($conditions);
        $order = $this->db->get('ct_order')->result_array();
        foreach ($order as $key => $value) {
            $order[$key] = $this->selOrderDetail($value);
        }
        return $order;
    }


    function selOrder($orderId) {
        $this->db->where('id', $orderId);
        log_message('info', '取得訂單, orderId:' . $orderId); // test log
        
        return $this->db->get('ct_order')->row_array();
    }


    function selOrderDetail($order) {
        $this->db->where('order_id', $order['id']);
        log_message('info', '取得訂單明細, orderId:' . $order['id']); // test log
        
        return $this->db->get('ct_order_detail')->row_array();
    }


    function insOrder($orderDataArray) {
        $this->db->insert('ct_order',$orderDataArray);
        $affectedRows = $this->db->affected_rows();
        return $affectedRows;
    }

    function insOrderDetail($orderDetailDataArray) {
        $affectedRows = 0;
        foreach ($orderDetailDataArray as $key => $value) {
            $this->db->insert('ct_order_detail', $value);
            log_message('info', '新增orderdetail, orderDetailId:' . $value['id']);
            $affectedRows += $this->db->affected_rows();
        }

        return $affectedRows;
    }

    function updOrder($orderId, $dataArray) {
        $this->db->where('order_id', $orderId);
        log_message('info', '更新訂單, orderId:' . $orderId); // test log
        $this->db->update('ct_order', $dataArray);
        $affectedRows = $this->db->affected_rows();
        
        return $affectedRows;
    }


    function deleteOrder($orderId) {
        $this->db->where('order_id', $orderId);
        log_message('info', '刪除訂單, orderId:' . $orderId); // test log
        return $this->db->delete('ct_order');
    }


    function checkOrderExist($orderId, $orderStatus) {
        if (strtoupper($orderStatus) == "ALL") {
            $this->db->where('order_id', $orderId);
            log_message('info', '檢查訂單, orderId:' . $orderId);  // test log
        } else {
            $conditions = "order_id='$orderId' AND status='$orderStatus'";
            $this->db->where($conditions);
            log_message('info', '檢查特定狀態訂單, orderId:' . $orderId); 
        }

        $order = $this->db->get('ct_order')->result_array();
        log_message('info', 'order content: ' . $order . ', sizeOf: ' . sizeof($order));  // test log

        return ($order ? true : false);
    }
}


?>