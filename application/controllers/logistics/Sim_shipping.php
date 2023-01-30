<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 *顧客別SIM発送用コントローラー
 */
class Sim_shipping extends CI_Controller {
    
    /**
     * 顧客別SIM発送画面
     */
    public function index()
    {
        $this->load->view('under_construction');
    }
    
}