<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * API新規オーダー一覧用コントローラー
 */
class Sim extends CI_Controller {
    
    /**
     * API新規オーダー一覧画面
     */
    public function index()
    {
        $this->load->view('under_construction');
    }
    
}