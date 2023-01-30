<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * IoTデバイスステータス一覧用コントローラー
 */
class Device extends CI_Controller {
    
    /**
     * IoTデバイスステータス一覧画面
     */
    public function index()
    {
        $this->load->view('under_construction');
    }
    
}