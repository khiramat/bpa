<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * IoTデバイス発注管理用コントローラー
 */
class Device_ordering extends CI_Controller {
    
    /**
     * IoTデバイス発注管理
     */
    public function index()
    {
        $this->load->view('under_construction');
    }
    
}