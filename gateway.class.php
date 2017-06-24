<?php

/**
 * CubeCart v6
 * ========================================
 * CubeCart is a registered trade mark of CubeCart Limited
 * Copyright CubeCart Limited 2014. All rights reserved.
 * UK Private Limited Company No. 5323904
 * ========================================
 * Web:   http://www.cubecart.com
 * Email:  sales@cubecart.com
 * License:  GPL-3.0 http://opensource.org/licenses/GPL-3.0
 */
require_once __DIR__ . '/includes/billplz.php';

class Gateway {

    private $_config;
    private $_module;
    private $_basket;

    public function __construct($module = false, $basket = false) {
        $this->_config = & $GLOBALS['config'];
        $this->_session = & $GLOBALS['user'];

        $this->_module = $module;
        $this->_basket = & $GLOBALS['cart']->basket;
    }

    ##################################################

    public function transfer() {

        $name = $this->_basket['billing_address']['first_name'] . ' ' . $this->_basket['billing_address']['last_name'];
        $email = $this->_basket['billing_address']['email'];
        $mobile = $this->_basket['billing_address']['phone'];
        $deliver = $this->_module['send_bill'];
        $collection_id = $this->_module['collection_id'];
        $order_id = $this->_basket['cart_order_id'];
        $description = $this->_module['billplz_description'];
        $plugin_folder_name = basename(__DIR__);
        $callback_url = $GLOBALS['storeURL'] . '/index.php?_g=rm&type=gateway&cmd=call&module='.$plugin_folder_name;

        $billplz = new Billplz($this->_module['api_key']);
        $billplz
                ->setAmount($this->_basket['total'])
                ->setCollection($collection_id)
                ->setDeliver($deliver)
                ->setDescription($description)
                ->setEmail($email)
                ->setMobile($mobile)
                ->setName($name)
                ->setPassbackURL($callback_url, $callback_url)
                ->setReference_1_Label('ID')
                ->setReference_1($order_id)
                ->create_bill(true);

        $transfer = array(
            'action' => $billplz->getURL(),
            'method' => 'get',
            'target' => '_self',
            'submit' => 'auto',
        );
        return $transfer;
    }

    public function repeatVariables() {
        return false;
    }

    public function fixedVariables() {
        return false;
    }

    ##################################################

    public function call() {

        if (substr(CC_VERSION, 0, 1) == '6' || $GLOBALS['db']->count('CubeCart_modules', 'module_id', array('module' => 'gateway', 'status' => '1')) == 1) {
            $cancel_return = 'confirm';
        } else {
            $cancel_return = 'gateway';
        }

        $signkey = $this->_module['x_signature'];
        $api_key = $this->_module['api_key'];

        if (isset($_GET['billplz']['id'])) {
            $data = Billplz::getRedirectData($signkey);
        } else if (isset($_POST['id'])) {
            $data = Billplz::getCallbackData($signkey);
            sleep(10);
        } else {
            exit;
        }

        $billplz = new Billplz($api_key);
        $moreData = $billplz->check_bill($data['id']);

        $cart_order_id = $moreData['reference_1'];

        $order = Order::getInstance();
        $order_summary = $order->getSummary($cart_order_id);
        $transData['notes'] = array();

        if ($data['paid']) {
            $transData['notes'][] = "Payment successful. <br />Bill ID: " . $data['id'] . "<br />Bill URL: " . $moreData['url'] . "<br />Status: " . strtoupper($moreData['state']);

            /*
             * Prevent from updating twice
             */
            if ($order_summary['status'] === '2') {
                $transData['notes'] = 'Ignoring duplicate callback.';
            } else {
                $transData['bill_id_status'] = $data['id'] . 'paid';
                $order->paymentStatus(Order::PAYMENT_SUCCESS, $cart_order_id);
                $order->orderStatus(Order::ORDER_PROCESS, $cart_order_id);
            }
        } else {
            $transData['notes'][] = "Payment Cancelled. <br />Bill ID: " . $data['id'] . "<br />Bill URL: " . $moreData['url'] . "<br />Status: " . strtoupper($moreData['state']);
            $order->paymentStatus(Order::PAYMENT_CANCEL, $cart_order_id);
            $order->orderStatus(Order::ORDER_CANCELLED, $cart_order_id);
        }

        $transData['gateway'] = 'Billplz';
        $transData['order_id'] = $cart_order_id;
        $transData['bill_id'] = $data['id'];
        $transData['paid_amount'] = number_format($moreData['paid_amount'] / 100, 2);
        $transData['amount'] = number_format($moreData['amount'] / 100, 2);
        $transData['status'] = $moreData['status'];
        $transData['customer_id'] = $order_summary['customer_id'];
        $order->logTransaction($transData);

        if (isset($_GET['billplz']['id'])) {
            if ($data['paid']) {
                $this->goToRedirect($GLOBALS['storeURL'] . '/index.php?_a=complete');
            } else {
                $this->goToRedirect($GLOBALS['storeURL'] . '/index.php?_a=' . $cancel_return);
            }
        } else {
            echo 'OK';
        }
        return false;
    }

    private function goToRedirect($url) {
        if (!headers_sent()) {
            header('Location: ' . $url);
        } else {
            echo "If you are not redirected, please click <a href=" . '"' . $url . '"' . " target='_self'>Here</a><br />"
            . "<script>location.href = '" . $url . "'</script>";
        }
    }

    public function process() {
        ## We're being returned from Billplz - This function can do some pre-processing, but must assume NO variables are being passed around
        ## The basket will be emptied when we get to _a=complete, and the status isn't Failed/Declined
        ## Redirect to _a=complete, and drop out unneeded variables
        httpredir(currentPage(array('_g', 'type', 'cmd', 'module'), array('_a' => 'complete')));
    }

    public function form() {
        return false;
    }

}
