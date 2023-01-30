<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * API開通待ちSIM一覧用コントローラー
 */
class Open_waiting extends CI_Controller
{

	/**
	 * API開通待ちSIM一覧画面
	 */
	public function index($cur_page = 1)
	{
		//$this->output->enable_profiler(TRUE);

		//         -------
		$this -> load -> library('pagination');
		$this -> load -> helper('property');
		$this -> load -> model('input_data_model');
		$this -> load -> model('input_list_model');

		// 1ページで表示件数
		if($this -> session -> has_userdata('order_open_waiting_per_page')){
			$per_page = $this -> session -> userdata('order_open_waiting_per_page');
		} else {
			$per_page = array_keys(get_select_property('page_size'))[0];
		}
		// 検索条件の作成
		if($this -> session -> has_userdata('order_open_waiting_search_condition')){
			$search_condition = $this -> session -> userdata('order_open_waiting_search_condition');
		}

		//取得条件
		$search_condition['a.DELETE_FLAG']     = DELETE_FLG_NOT_DELETE;   // 未削除
		$search_condition["a.CALL_STATUS"]     = CALL_STATUS_DONE; // 実行完了　：　3
		$search_condition["a.HANKURO_SAKUSEI"] = 0; // 1:半黒作成 0:初期値
//         $search_condition["a.ALADIN_RESULT"] = RESULT_OK;
		$search_condition["a.API_STATUS"]      = 2; // 完了
		$search_condition["a.transactionTYPE"] = "A2"; // 一旦、半黒作成件のみ取得　
		$search_condition["a.ryokinplan !="]   = "NULL"; // 「新規申込(半黒ROM作成)とMNP転入受付(半黒ROM作成)」→　料金プランが存在かつトランザクションタイプがA2の場合

		// あいまい検索条件
		if($this -> session -> has_userdata('order_open_waiting_search_target')){
			$search_target = $this -> session -> userdata('order_open_waiting_search_target');
		} else {
			$search_target = null;
		}

		$total_rows = $this -> input_list_model -> count_4_hankuro_sim($search_condition, $search_target);

		if($total_rows > 0){
			// current page
			$max_page = ceil($total_rows / $per_page);
			if($cur_page > $max_page){
				$cur_page = $max_page;
			}

			// pagination初期化
			$config['base_url']   = site_url('order/open_waiting');
			$config['total_rows'] = $total_rows;
			$config['per_page']   = $per_page;
			$this -> pagination -> initialize($config);

			// ページデータの取得
			$input_data = $this -> input_list_model -> select_hankuro_sim($per_page,
				($cur_page - 1) * $per_page, $search_condition, $search_target
			);

			// 返却結果
			$data['page_links'] = $this -> pagination -> create_links();
			$data['input_data'] = $input_data;
		}

		// 返却結果
		$data['cur_page']      = $cur_page;
		$data['per_page']      = $per_page;
		$data['total_rows']    = $total_rows;
		$data['search_target'] = $search_target;
		if(!empty($search_condition)){
			// 検索日付(始)
			if(isset($search_condition['a.UPDATE_DATETIME >='])){
				$data['time_from'] = date('Ymd', strtotime($search_condition['a.UPDATE_DATETIME >=']));
			} else {
				$data['time_from'] = '';
			}
			// 検索日付(終)
			if(isset($search_condition['a.UPDATE_DATETIME <='])){
				$data['time_to'] = date('Ymd', strtotime($search_condition['a.UPDATE_DATETIME <=']));
			} else {
				$data['time_to'] = '';
			}
			// カード形状
			$data['cardkeijo'] = isset($search_condition['a.cardkeijo']) ? $search_condition['a.cardkeijo'] : '';
			// 入力ステータス
			$data['seizobango'] = isset($search_condition['d.seizobango']) ? $search_condition['d.seizobango'] : ''; // 2do zz -> a.seizobango
			// コールステータス
			$data['sim_status'] = isset($search_condition['d.sim_status']) ? $search_condition['d.sim_status'] : '';
		} else {
			// 検索日付(始)
			$data['time_from'] = '';
			// 検索日付(終)
			$data['time_to'] = '';
			// トランザクションタイプ
			//             $data['transactionTYPE'] = '';
			// カード形状
			$data['cardkeijo'] = '';
			// 製造番号
			$data['seizobango'] = '';
			// SIMステータス
			$data['sim_status'] = '';
		}
		// -------


		$this -> load -> view('order/open_waiting', $data);
	}

	/**
	 * 検索処理
	 */
	public function do_search()
	{
		$condition = null;
		// 検索日付 (始)
		$time_from = htmlspecialchars(trim($this -> input -> post('time_from', true)));
		if($time_from !== '' and $this -> form_validation -> valid_date($time_from, 'Y/m/d')){
			$condition['a.UPDATE_DATETIME >='] = date('Y-m-d H:i:s', strtotime($time_from));
		}
		// 検索日付 (終)
		$time_to = htmlspecialchars(trim($this -> input -> post('time_to', true)));
		if($time_to !== '' and $this -> form_validation -> valid_date($time_to, 'Y/m/d')){
			$condition['a.UPDATE_DATETIME <='] = date('Y-m-d H:i:s', strtotime($time_to . '235959'));
		}
		// カード形状
		$cardkeijo = htmlspecialchars(trim($this -> input -> post('cardkeijo', true)));
		if($cardkeijo !== ''){
			$condition["a.cardkeijo"] = $cardkeijo;
		}
		// 製造番号
		$seizobango = htmlspecialchars(trim($this -> input -> post('seizobango', true)));
		if($seizobango !== ''){
			$condition["a.seizobango"] = $seizobango;
		}
		// SIM作成済
		$sim_status = htmlspecialchars(trim($this -> input -> post('sim_status', true)));
		if($sim_status !== ''){
			$condition["d.sim_status"] = $sim_status;
		}

		// set session
		if($condition != null){
			$this -> session -> set_userdata('order_open_waiting_search_condition', $condition);
		} else {
			$this -> session -> unset_userdata('order_open_waiting_search_condition');
		}
		// 検索項目
		$search_target = htmlspecialchars(trim($this -> input -> post('search_target', true)));
		if(!empty($search_target)){
			$this -> session -> set_userdata('order_open_waiting_search_target', $search_target);
		} else {
			$this -> session -> unset_userdata('order_open_waiting_search_target');
		}

		// API開通待ちSIM一覧
		redirect('order/open_waiting', 'location');
	}

	/**
	 * ページサイズ変更処理
	 */
	public function set_page_size()
	{
		$this -> load -> helper('property');

		$per_page  = htmlspecialchars(trim($this -> input -> post('per_page', true)));
		$page_size = array_keys(get_select_property('page_size'));
		if(!in_array($per_page, $page_size)){
			$per_page = $page_size[0];
		}
		$this -> session -> set_userdata('order_open_waiting_per_page', $per_page);

		// API開通待ちSIM一覧
		redirect('order/open_waiting', 'location');
	}

	/**
	 * @name open_hankuro_sim
	 * @desc 開通待ちSIMの開通処理
	 * @desc 未開通状態の「新規、MNP、再発行」の件を開通する。
	 * @param　post ajax
	 * @return array
	 */
	public function open_hankuro_sim()
	{
		$this -> load -> model('input_data_model');
		$this -> load -> model('input_list_model');
		//change stop flag
		$ids = $this -> input -> post("IDs"); // get Ajax-post-data by「実行」ボタン from view

		if(empty($ids)){
			return; // 選択件がない場合
		}
		$id_array = explode(',', $ids);

		$input_data_uid_array = array();
		foreach($id_array as $id){
			array_push($input_data_uid_array, $this -> input_list_model -> convert_id($id)); // INPUT_LIST_UID -> INPUT_DATA_UID/
		}

		$this -> input_data_model -> set_status_4_hankuro_sim($input_data_uid_array); // INPUT_DATA.UID
//         $count = $this->input_list_model->set_status_4_hankuro_sim($id_array); //　削除要

		$count = $this -> input_list_model -> add_m02_4_hankuro_sim($id_array); // 半黒開通

		//以下はAPI新規オーダー一覧に未開通→開通件を表示するコードだが、既存のやつと紛れしまうので使わない。
		//$count = $this->input_data_model->add_m02_4_hankuro_sim($id_array); // for INPUT_LIST.UID 方針によって、キャンセル

		echo json_encode($count);
	}

	public function destroy()
	{
		$this -> session -> sess_destroy();
	}

}