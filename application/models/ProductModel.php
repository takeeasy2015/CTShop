<?php


    class ProductModel extends CI_Model {
        public function __construct() {
            parent::__construct();
        }


        /*
        **  category
        */

        // 取得特定商品分類
        function getCategory($categoryId) {
            $this->db->where('id', $categoryId);
            log_message('debug', '取得商品分類, categoryId:' . $categoryId); // test log
            return $this->db->get('ct_product_category')->row_array();
        }

        function insCategory($categoryDataArray) {
            log_message('debug', '新增商品分類, name:' . $categoryDataArray['categoryName']); // test log
            
            return $this->db->insert('ct_product_category', $categoryDataArray);
        }



        /*
        **  Product
        */

        // 取得特定商品
        function selProduct($productNum) {
            $this->db->where('num', $productNum);
            log_message('debug', '取得商品, productNum:' . $productNum); // test log
            
            return $this->db->get('ct_product')->row_array();
        }

        // 新增商品
        function insProduct($productDataArray) {
            log_message('debug', '新增商品, title:' .$productDataArray['productTitle']); // test log
           
            return $this->db->insert('ct_product', $productDataArray);
        }

        // 更新商品
        function updProduct($productNum, $productDataArray) {
            $this->db->where('num', $productNum);  // 先找出特定商品
            log_message('debug', '準備更新商品, productNum:' . $productNum); // test log
            
            return $this->db->update('ct_product', $productDataArray);  // 再更新內容
        }

        // 刪除商品
        function delProduct($productNum) {
            $this->db->where('num', $productNum);  // 先找出特定商品
            log_message('debug', '準備刪除商品, productNum:' . $productNum); // test log
            
            return $this->db->delete('ct_product');
        }


        function checkExist($productNum) {
            $this->db->where('num', $productNum);
            $result = $this->db->get('ct_product')->row_array();
            return ($result ? true : false);
        }
    
        function checkStock($productNum, $qty) {
            $this->db->where('num', $productNum);
            $result = $this->db->get('ct_product')->row_array();
            if ($result['safety_stock'] >= $qty) {
                return true;
            } else {
                return false;
            }
        }

        
    }
?>