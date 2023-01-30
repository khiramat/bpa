<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * SIM発注管理用コントローラー
 */
class Sim_ordering extends CI_Controller {
    
    /**
     * SIM発注管理
     */
    public function index()
    {
        $this->load->view('under_construction');
    }
    
}