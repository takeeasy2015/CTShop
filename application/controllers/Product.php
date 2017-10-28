<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product extends CI_Controller {

    public function __construct() {
        parent::__construct();
        
        $this->load->model('ProductModel');
        // $this->load->model('CatrgoryModel');
    }


    /**
     * Category 
     */
    



    /**
     * Product
     */

    function getProduct($productNum) {
       
        $view_data['product'] = $this->ProductModel->getProduct($productNum);

        if ($view_data['product'] == null) {
            log_message('debug', 'product ' . $productNum . ' is empty.');
        } else {
            log_message('debug', 'product ' . $productNum . ' get.');
        }

        $this->load->view('product/page', $view_data);

    }


    function insertProduct() {

    }


}

?>



