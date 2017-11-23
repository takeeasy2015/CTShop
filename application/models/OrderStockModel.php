<?php 
    
    
class OrderStockModel extends CI_Model {
    public function __construct() {
        parent::__construct();

        
    }


    function updSafetyStock($productNum, $productQty) {
        $modifyTime = date('Y-m-d H:i:s');
        $updSafetyStockSql = " UPDATE ct_product ". 
               "    SET safety_stock = safety_stock + ".$this->db->escape($productQty). 
               "      , modify_time = ".$this->db->escape($modifyTime).
               "  WHERE num = ".$productNum." AND (safety_stock + ".$this->db->escape($productQty).") >= 0";
        $this->db->query($updSafetyStockSql);
        $affectedRows = $this->db->affected_rows();
        return $affectedRows;
    }

    function updStock($productNum, $productQty) {
        $modifyTime = date('Y-m-d H:i:s');
        $updStockSql = " UPDATE ct_product ". 
               "    SET stock = stock + ".$this->db->escape($productQty). 
               "      , modify_time = ".$this->db->escape($modifyTime).
               "  WHERE num = ".$productNum." AND (stock + ".$this->db->escape($productQty).") >= 0";
        $this->db->query($updStockSql);
        $affectedRows = $this->db->affected_rows();
        return $affectedRows;
    }
   
}   
?>  