<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * IoTデバイス在庫管理用コントローラー
 */
class Device_stock extends CI_Controller {
    
    /**
     * oTデバイス在庫管理画面
     */
    public function index($cur_page = 1)
    {
        $this->load->view('under_construction');
    }
    
}
