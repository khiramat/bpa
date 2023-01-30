<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * ホーム用コントローラー
 */
class Home extends CI_Controller {
    
    /**
     * 入力データ一覧
     */
    /*
	public function index_bck()
	{
		// TODO
		redirect('order/order_list', 'location');
	}
*/
	public function index()
	{
		$this->load->view('home/index');
	}

}