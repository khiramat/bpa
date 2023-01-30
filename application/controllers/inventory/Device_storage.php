<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * IoTデバイス入庫用コントローラー
 */
class Device_storage extends CI_Controller {
    
    /**
     * IoTデバイス入庫画面
     */
    public function index($cur_page = 1)
    {
        $this->load->view('under_construction');
    }
    
}
