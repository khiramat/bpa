<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * トラフィック分析用コントローラー
 */
class Traffic extends CI_Controller {
    
    /**
     * トラフィック分析画面
     */
    public function index()
    {
        $this->load->view('under_construction');
    }
    
}