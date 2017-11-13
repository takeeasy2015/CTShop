<?php 

class OrderModel extends CI_Model {

    public function __construct() {
        parent::__construct();

        
    }

    function createOrder($orderDataArray, $orderDetailArray) {
        $code_date = str_replace('-', '', $orderDataArray['create_date']);  // Y-m-d  -> Ymd
        $code_time = substr(str_replace(':', '', $orderDataArray['create_time']), 0, 4);  // H:i:s  ->  Hi
        
        $this->db->trans_begin();

        // 產生新的訂單號碼
        $newOrderId = $this->genThisOrderId($code_date, $code_time);
        if (empty($newOrderId)) {
            log_message('info', '產生訂單失敗, orderid:' . $newOrderId);
            $this->db->trans_commit();
            return "";
        }
        $orderDataArray['id'] = $newOrderId;
        $orderDataArray['id_uni'] = sha1($orderDataArray['id']);
        
        // 產生新的訂單明細號碼
        foreach($orderDetailArray as $key => $value) {
            $orderDetailArray[$key]['id'] = uniqid();
            $orderDetailArray[$key]['order_id'] = $newOrderId;
            log_message("debug", "key: ". $key . ", 訂單明細的ID: " .  $orderDetailArray[$key]['id'] . ", 訂單ID: " . $orderDetailArray[$key]['order_id']); // test log
        }

        foreach($orderDetailArray as $key => $value) {
            foreach($value as $id => $str) {
                log_message("debug", "detail  id: " . $id . ", str: " . $str); // test log
            }
        }

        // 成立訂單
        if ($this->insOrder($orderDataArray) < 1) {
            log_message('info', '新增訂單失敗, orderid:' . $orderDataArray['id']);
            $this->db->trans_rollback();
            return "";
        }
        
        log_message('info', '準備新增orderdetail, orderid:' . $orderDataArray['id']);
        
        if ($this->insOrderDetail($orderDetailArray) < 1) {
            log_message('info', '新增訂單明細失敗, orderid:' . $orderDetailArray['order_id']['id']);
            $this->db->trans_rollback();
            return "";
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            log_message('info', '訂單rollback, orderid:' . $orderDataArray['id']);
            return "";
        } else {
            $this->db->trans_commit();
            log_message('info', '訂單commit, orderid:' . $orderDataArray['id']);
        }

        return $newOrderId;
    }

    
    function selUserOrder($orderId, $buyerPhone) {
        $conditions = "id='$orderId' AND buyer_phone='$buyerPhone'";
        $this->db->where($conditions);
        $order = $this->db->get('ct_order')->row_array();

        return (empty($order) ? array(): $this->selOrderDetail($order));
    }


    function selOrder($orderId) {
        $this->db->where('id', $orderId);
        log_message('info', '撈取訂單, orderId:' . $orderId); // test log
        $order = $this->db->get('ct_order')->row_array();
        
        return (empty($order) ? array(): $this->selOrderDetail($order));
    }


    function selOrderDetail($order) {
        $this->db->where('order_id', $order['id']);
        log_message('info', '撈取訂單明細, orderId:' . $order['id']); // test log
        
        $orderDetail = $this->db->get('ct_order_detail')->result_array();
        $order['total'] = 0;
        if (empty($orderDetail)) {
            $order['orderDetail'] = array();
            log_message('info', '查無訂單明細, orderId:' . $order['id']); // test log
        } else {
            $order['orderDetail'] = $orderDetail;
            foreach ($orderDetail as $key => $value) {
                $order['total'] = $order['total'] + $value['product_price'] * $value['product_qty'];
            }
            log_message('info', '已取得訂單明細, orderId:' . $order['id']); // test log
        }
        
        return $order;
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
        $this->db->where('id', $orderId);
        log_message('info', '更新訂單, orderId:' . $orderId); // test log
        $this->db->update('ct_order', $dataArray);
        $affectedRows = $this->db->affected_rows();
        
        return $affectedRows;
    }


    function deleteOrder($orderId) {
        $this->db->where('id', $orderId);
        log_message('info', '刪除訂單, orderId:' . $orderId); // test log
        $this->db->delete('ct_order');
        $affectedRows = $this->db->affected_rows();
        return $affectedRows;
    }


    function checkOrderExist($orderId, $orderStatus) {
        $conditions = "";
        if (strtoupper($orderStatus) == "ALL") {
            $conditions = "id='$orderId'";
            log_message('debug', '檢查訂單, orderId:' . $orderId);  // test log
        } else {
            $conditions = "id='$orderId' AND status='$orderStatus'";
            log_message('debug', '檢查特定狀態訂單, orderId:' . $orderId . ", status: " . $orderStatus); 
        }

        $this->db->where($conditions);
        $order = $this->db->get('ct_order')->row_array();
        log_message('debug', '訂單查詢結果,orderId: ' . $orderId . ', order is empty: ' . empty($order));

        return (empty($order) ? false : true);
    }

    // 產生訂單編號
    function genThisOrderId($codeDate, $codeTime) {
        $newOrderId = "";
        
        // $this->db->trans_begin();
        $sql = 
        " INSERT INTO ct_order_autoencode (code_type, code_head, code_date, code_time, code_count) " . 
        " VALUES (
            ".$this->db->escape(ShopConstants::ORDERIDRULE_TYPE).", 
            ".$this->db->escape(ShopConstants::ORDERIDRULE_HEADER).",
            ".$this->db->escape($codeDate).",
            ".$this->db->escape($codeTime).", " .
            "(SELECT CASE WHEN MAX(b.code_count) IS NULL THEN 1 ELSE MAX(b.code_count)+1 END 
                FROM (SELECT code_count FROM ct_order_autoencode where code_date = ".$this->db->escape($codeDate).") as b)" . 
        ");";
        log_message('debug', $sql); // test log
        
        $this->db->query($sql);
        $count = $this->db->affected_rows();

        if ($count > 0) {
            $sql2 = 
            " SELECT code_type, code_head, code_date, code_time, code_count, code_maxlen " . 
            "   FROM ct_order_autoencode a " . 
            "  WHERE a.code_type = ".$this->db->escape(ShopConstants::ORDERIDRULE_TYPE).
            "    AND code_date = ".$this->db->escape($codeDate)." ORDER BY code_count DESC LIMIT 1; ";            
            $code = $this->db->query($sql2)->row_array();
            if (!empty($code)) {
                $serialNum = str_pad($code['code_count'], 3, "0", STR_PAD_LEFT);
                $newOrderId = $code['code_head'] . $code['code_date'] . $code['code_time'] . $serialNum;
                log_message("debug", "newOrderId: " .  $newOrderId); // test log
            }
        }

        // if ($this->db->trans_status() === FALSE) {
        //     $this->db->trans_rollback();
        //     log_message('info', '產生訂單編號rollback, newOrderId: ' . $newOrderId);
        //     return false;
        // } else {
        //     $this->db->trans_commit();
        //     log_message('info', '產生訂單編號commit, newOrderId: ' . $newOrderId);
        // }

        return $newOrderId;
    }


    // 寄信
    function sendCompleteMail($toMail, $ccMail, $bccMail) {
        if ($toMail == '') { 
            log_message("debug", "無效寄信, toMail target is empty");
            return false;
        } else {
            $this->email->from('testmybaby00@gmail.com', 'Service');
            $this->email->to($toMail);
            if ($ccMail != '') {
                $this->email->cc($ccMail);
            }
            if ($bccMail != '') {
                $this->email->bcc($bccMail);
            }
            $this->email->subject('訂購完成');
            $this->email->message('恭喜您完成訂購');
            $this->email->set_alt_message("沒有HTML格式的信件內文");
            return $this->email->send();
        }

    }


    function mailContent() {
        $completeOrderMsg = '<p>感謝您的訂購</p>';
        
        
        $mailMsg = '';
        $html .= ' <!DOCTYPE html> <html lang="en"> ';
        $head .= '<head>';
        $headEnd .= '</head>';
        $body .= '<body>';
        $bodyEnd .= '</body></html>';

        $mailMsg .= $html . $head;
        $mailMsg .= $headEnd;
        $mailMsg .= $body;
        $mailMsg .= $completeOrderMsg;
        $mailMsg .= $bodyEnd;
        
    }
}


?>