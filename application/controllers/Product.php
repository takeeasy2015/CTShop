<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Product extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        
        $this->load->model('ProductModel');
        // $this->load->model('CatrgoryModel');
    }

    function index()
    {
        // 頁面資訊
        $view_data = array(
            'title'=> "CTShop",
            'path'=> 'product/',
            'page'=> 'page.php'
        );
        $view_data['cart'] = $this->cart->contents(true);

        $this->load->view('layout', $view_data);
    }

    /**
     * Category
     */
    



    /**
     * Product
     */

    function getProduct($productNum)
    {
        $view_data = array(
            'title'=> "CTShop - Product",
            'path' => 'product/',
            'page'=> 'page.php',
        );

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

        $view_data['product'] = $this->ProductModel->getProduct($productNum);

        if ($view_data['product'] == null) {
            log_message('debug', 'product ' . $productNum . ' is empty.');   // test log
        } else {
            log_message('debug', 'product ' . $productNum . ' get.');   // test log
        }

        $this->load->view('layout', $view_data);
    }


    function insertProduct()
    {
    }


    /**
     * Cart
     */
    
    function addToCart()
    {
    }
}
