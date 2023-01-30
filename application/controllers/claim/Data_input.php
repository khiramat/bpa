<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * 顧客別請求データ入力用コントローラー
 */
class Data_input extends CI_Controller {
    
    /**
     * 顧客別請求データ入力画面
     */
    public function index()
    {
        $this->load->view('under_construction');
    }
    
}