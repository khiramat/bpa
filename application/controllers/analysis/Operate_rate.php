<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * SIM稼働率用コントローラー
 */
class Operate_rate extends CI_Controller {
	/**
	 * API開通待ちSIM一覧画面
	 */
	public function index($cur_page = 1)
	{
		//$this->output->enable_profiler(TRUE);

		//         -------
		$this->load->model('input_data_model');
		$this->load->model('input_list_model');

		//取得条件
		$search_condition['a.DELETE_FLAG']     = DELETE_FLG_NOT_DELETE;   // 未削除
		$search_condition["a.CALL_STATUS"]     = CALL_STATUS_DONE; // 実行完了　：　3
		$search_condition["a.HANKURO_SAKUSEI"] = 0; // 1:半黒作成 0:初期値
		$search_condition["a.API_STATUS"]      = 1; // 完了
		$search_condition["a.API_STATUS"]      = 2; // 完了
		$search_condition["a.transactionTYPE"] = "A2"; // 一旦、半黒作成件のみ取得　
		$search_condition["a.INPUT_DATA_UID !="] = 1273;


		$data = '';
		$this->load->view('analytics/operate_rate', $data);
	}
}