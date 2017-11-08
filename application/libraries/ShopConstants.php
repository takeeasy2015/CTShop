<?php

class ShopConstants {
    const SHIPPING_FEE = 60;

    /**
     * payment
     */
    // 信用卡
    const CREDIT_CARD = 0;
    const OTHER_PAYMENT = 99;

    /**
     * order status
     */
    const ORDERSTATUS_UNPAID = '0';
    const ORDERSTATUS_PAID = '1';
    const ORDERSTATUS_BESHIPPED = '2';
    const ORDERSTATUS_SHIPPING = '3';
    const ORDERSTATUS_COMPLETED = '4';
    const ORDERSTATUS_ALL = 'ALL';


    /**
     * ECpay
     */
    const SERVICEURL = "https://payment.ecpay.com.tw/Cashier/AioCheckOut/V5";
    const HASHKEY = "5294y06JbISpM5x9";
    const HASHIV = "v77hoKGq4kWxNNIS";
    const MERCHANTID = "2000132";

    const SERVICEURL_TEST = "https://payment-stage.ecpay.com.tw/Cashier/AioCheckOut/V5";
    const HASHKEY_TEST = "5294y06JbISpM5x9";
    const HASHIV_TEST = "v77hoKGq4kWxNNIS";
    const MERCHANTID_TEST = "2000132";

}

?>