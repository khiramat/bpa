<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once 'Abstract_model.php';

/**
 * 入力データテーブル用モデル
 */
class Input_data_model extends Abstract_model
{

	protected $table_name = 'INPUT_DATA';

	/**
	 * コンストラクタ
	 */
	public function __construct()
	{
		// table name
		parent::__construct($this->table_name);
	}

	/**
	 * 主キーよって、データの取得処理
	 * @param string $uid
	 */
	public function select_one(string $uid)
	{

		$data_list = $this->db->select('a.*, b.TENANT_NAME')
			->from($this->table_name . ' as a')
			->join('TENANT b', 'a.TENANT_ID = b.TENANT_ID')
			->where('UID', "$uid")
			->get()->result();

		return empty($data_list) ? null : $data_list[0];
	}

	/**
	 * 主キーよって、データの取得処理
	 * @param string $uid
	 */
	public function select_order(string $uid)
	{

		return $this->db->select('*')
			->from($this->table_name)
			->where('UID', $uid)
			->get()->result();
	}


	/**
	 * 入力ステータスの更新処理
	 * @param array $data
	 * @param array $condition
	 * @return int 更新件数
	 */
	public function update(array $data, array $condition): int
	{
		$data['UPDATE_DATETIME'] = date_format(date_create(), 'Y-m-d H:i:s');
		return parent::update($data, $condition);
	}

	/**
	 * 入力ステータスの削除処理
	 * @param array $condition
	 * @param bool $logical_del_flg 論理削除フラグ
	 * @return int
	 */
	public function delete(array $condition, bool $logical_del_flg = true): int
	{
		// 論理削除の場合
		if ($logical_del_flg) {
			return $this->update(array('DELETE_FLAG' => DELETE_FLG_DELETED), $condition);
		}

		return parent::delete($condition);
	}

	/**
	 * 入力データが中断かどうかの判断
	 * @param $uid
	 * @return bool
	 */
	public function is_stopped($uid): bool
	{
		$condition = array(
			'UID'         => $uid,
			'CALL_STATUS' => CALL_STATUS_STOP  // ステータス: 中断
		);

		return parent::count($condition) > 0 ? true : false;
	}

	/**
	 * @desc 未処理件を取得
	 * @return array of object
	 */
	public function get_unprocessed_rows(): array
	{
		$condition = array(
			'INPUT_STATUS' => INPUT_STATUS_UNDO
		);

		return parent::select_list($condition);
	}

	/**
	 * @desc ステータスの変更
	 * @param uid
	 * @return int
	 */
	public function change_input_status(string $input_status, string $uid): int
	{
		$data      = array(
			'INPUT_STATUS' => $input_status
		);
		$condition = array(
			'UID' => $uid
		);

		return parent::update($data, $condition);
	}

	/**
	 * データ件数の取得
	 *
	 * @param array $condition
	 * @param string $search_target
	 * @return int
	 */
	public function count(array $condition = null, string $search_target = null): int
	{
		$this->db->from($this->table_name . ' as a');
		$this->db->join('TENANT b', 'a.TENANT_ID = b.TENANT_ID');
		// 検索条件
		if ($condition) {
			$this->db->group_start();
			foreach ($condition as $key => $value) {
				$this->db->where('a.' . $key, "$value");
			}
			$this->db->group_end();
		}

		// あいまい検索条件
		$this->set_like_condition($search_target);

		return $this->db->count_all_results();
	}

	/**
	 * バッチで次の処理可能のデータの取得
	 */
	public function get_next_input_data(array $exclude_uid_list = null)
	{
		log_message('info', '次の入力データを探しています。');

		$this->db->select('*')
			->from($this->table_name)
			->where('INPUT_STATUS', INPUT_STATUS_DONE)     // 入力ステータス: 入力完了
			->where('DELETE_FLAG', DELETE_FLG_NOT_DELETE)  // 削除フラグ: 未削除
			->where('CALL_STATUS', CALL_STATUS_RESERVE)   // コールステータス: 実行予約
			->group_start()
			->where('CALL_STATUS', CALL_STATUS_RESERVE)    // コールステータス: 実行予約
			->or_where('CALL_STATUS', CALL_STATUS_STOP)    // コールステータス: 中断
			->group_end();
		if (!empty($exclude_uid_list)) {
			$this->db->where_not_in('UID', $exclude_uid_list);
		}
		$data_list = $this->db->get()->result();
		if (empty($data_list)) {
			log_message('info', '処理できる入力データが見つかりませんでした。処理終了。');
			return null;
		}
		log_message('info', sprintf('処理中のシーケンスID: [%s], テナントID: [%s]', $data_list[0]->UID, $data_list[0]->TENANT_ID));

		$result = $data_list[0];

		// 入力データ: 実行予約から実行中に変更
		if ($result->CALL_STATUS == CALL_STATUS_RESERVE) {
			$this->update(
				array(
					'CALL_STATUS' => CALL_STATUS_DOING
				),
				array(
					'UID' => $result->UID
				));
		}
		return $result;
	}

	/**
	 * 入力データ一覧の取得
	 * @param int $limit
	 * @param int $start
	 * @param array $condition
	 * @param string $search_target
	 * @param array $order_by
	 * @return array 入力データ一覧
	 */
	public function select_page_datas(int $limit, int $start = 0, array $condition = null, string $search_target = null): array
	{
		$this->db->select('a.*, b.TENANT_NAME')->from($this->table_name . ' as a');
		$this->db->join('TENANT b', 'a.TENANT_ID = b.TENANT_ID');
		// 検索条件
		if ($condition) {
			foreach ($condition as $key => $value) {
				$this->db->where('a.' . $key, "$value");
			}
		}

		// あいまい検索条件
		$this->set_like_condition($search_target);
		// オーダー
		$this->db->order_by('a.UID', "DESC");
		// limit
		$this->db->limit($limit, $start);

		//         return $this->db->get()->result();
		try {
			return $this->db->get()->result();
		}
		catch (Exception $e) {
			$this->session->unset_userdata('acs_dashboard_list_search_target');
			return false;
		}
	}

	/**
	 * @name set_status
	 * @desc 「未実行」or「中断」ステータスを設定する。
	 * @param array
	 * @return void
	 */
	public function set_status(array $id_array, $status = "stop"): int
	{
		switch ($status) {
		case "stop":
			$data      = array(
				"CALL_STATUS" => CALL_STATUS_STOP
			);
			$condition = array(
				"INPUT_STATUS" => INPUT_STATUS_DONE,
				"CALL_STATUS"  => CALL_STATUS_RESERVE,
				"DELETE_FLAG"  => DELETE_FLG_NOT_DELETE
			);
			break;
		case "restart":
			$data      = array(
				"CALL_STATUS" => CALL_STATUS_RESERVE
			);
			$condition = array(
				"INPUT_STATUS" => INPUT_STATUS_DONE,
				"CALL_STATUS"  => CALL_STATUS_STOP,
				"DELETE_FLAG"  => DELETE_FLG_NOT_DELETE
			);
			break;
		case "reserve":
			$data      = array(
				"CALL_STATUS" => CALL_STATUS_RESERVE
			);
			$condition = array(
				"INPUT_STATUS" => INPUT_STATUS_DONE,
				"CALL_STATUS"  => CALL_STATUS_UNDO,
				"DELETE_FLAG"  => DELETE_FLG_NOT_DELETE
			);
		}

		$loop_count = 0;
		foreach ($id_array as $id) {
			$condition["UID"] = $id;
			$loop_count       += $this->update($data, $condition);
		}
		return $loop_count;
	}

	/**
	 * @name remove
	 * @desc 選択された件を削除する。
	 * @param array
	 * @return int
	 */
	public function remove(array $id_array): int
	{
		$condition = array(
			//"INPUT_STATUS" => INPUT_STATUS_DONE,
			//"CALL_STATUS" => CALL_STATUS_DONE
		);

		$loop_count = 0;
		for ($i = 0; $i < count($id_array); $i++) {
			$condition["UID"] = $id_array[$i];
			$loop_count       += $this->delete($condition);
		}
		return $loop_count;
	}

	/**
	 * @name set_status_4_unopened_sim
	 * @desc INPUT_LIST.UID毎に「実行予約」
	 * @param 　string
	 * @return string
	 */
	public function set_status_4_hankuro_sim(array $id_array): int
	{
		$data       = array(
			"CALL_STATUS" => CALL_STATUS_RESERVE
		);
		$condition  = array(
			//                 "CALL_STATUS" => CALL_STATUS_UNDO,
			'DELETE_FLAG' => DELETE_FLG_NOT_DELETE
		);
		$loop_count = 0;
		for ($i = 0; $i < count($id_array); $i++) {
			$condition["UID"] = $id_array[$i];
			$loop_count       += $this->update($data, $condition);
		}
		return $loop_count;
	}

	/**
	 * @name add_m02_hankuro_sim
	 * @desc A2のレコードをコピーして、M02のレコードで追加する。
	 * @param 　array INPUT_LIST.UID
	 * @return int 挿入件数
	 */
	public function add_m02_4_hankuro_sim(array $id_array)
	{ //: int
		$loop_count = 0;
		foreach ($id_array as $id) {
			$this->db->select('a.*, b.TENANT_ID')->from('INPUT_LIST as a');
			$this->db->join('INPUT_DATA b', 'a.INPUT_DATA_UID = b.UID');
			$this->db->join('TENANT c', 'b.TENANT_ID = c.TENANT_ID');
			$this->db->where('a.UID', 1);
			$m02 = $this->db->get()->result(); // 単数しか存在しない。

			$data_fields                    = array();
			$data_fields["UID"]             = null;                 // auto incrementなので、NULLに設定
			$data_fields['TENANT_ID']       = $m02[0]->TENANT_ID; // 単数しか存在しない。
			$data_fields['ryokinplan']      = $m02[0]->ryokinplan;
			$data_fields['cardkeijo']       = $m02[0]->cardkeijo;
			$data_fields["transactionTYPE"] = "M02";
			$data_fields['LINE_CNT']        = 1;
			$data_fields['denwabango']      = $m02[0]->denwabango;
			$data_fields['sousaservice']    = $m02[0]->sousaservice;
			$data_fields['INPUT_KBN']       = INPUT_KUBUN_PAGE;
			$data_fields['INPUT_FILE_NAME'] = $m02[0]->UID;
			$data_fields['CREATE_DATETIME'] = date_format(date_create(), 'Y-m-d H:i:s'); // 生成時間

			$loop_count += $this->insert($data_fields); // 累積挿入件数
		}

		return $loop_count;
	}

	// 2 do by zzPro -> 要らない

	/**
	 * あいまい条件の設定
	 * @param string $search_target
	 */
	private function set_like_condition(string $search_target = null)
	{
		if (!empty($search_target)) {
			$search_list = explode(' ', preg_replace('/¥s|　|、|,+/', ' ', $search_target));
			for ($i = 0; $i < count($search_list); $i++) {
				$this->db->group_start();
				$this->db->like('a.UID', $search_list[$i]);
				//$this->db->or_like('a.TENANT_ID', $search_list[$i]);
				$this->db->or_like('a.denwabango', $search_list[$i]);
				$this->db->or_like('a.LINE_CNT', $search_list[$i]);
				if (preg_match('/^([\d\-\/]+)$/', $search_list[$i]) != false) {
					$this->db->or_like('a.UPDATE_DATETIME', str_replace('/', '-', $search_list[$i]));
				}
				$this->db->or_like('b.TENANT_NAME', $search_list[$i]);
				$this->db->group_end();
			}
		}
	}

}