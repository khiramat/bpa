<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once 'Abstract_model.php';

/**
 * 入力データリストテーブル用モデル
 */
class Input_list_model extends Abstract_model {
    
    protected $table_name = 'INPUT_LIST';
    
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
    public function select_one(string $uid) {
        $data_list = parent::select_list(array(
                'UID' => $uid
        ));
        return empty($data_list) ? NULL : $data_list[0];
    }

  /**
   * 処理用データリストの取得処理
   * @param string $input_data_uid
   * @param int $limit
   * @param int $start
   */
  public function select_process_data(string $input_data_uid, int $limit, int $start = 0)
  {
    return $this->db->select('*')
      ->from($this->table_name)
      ->where('INPUT_DATA_UID', "$input_data_uid")
      ->where('CALL_STATUS', CALL_STATUS_RESERVE)           // コールステータス: 実行予約
      ->where('DELETE_FLAG', DELETE_FLG_NOT_DELETE)      // 削除フラグ: 未削除
      ->order_by('UPDATE_DATETIME ASC')
      ->limit($limit, $start)
      ->get()->result();
  }
  /**
   * 電話番号重複時の最大UID取得
   * @param string $denwabango
   */
  public function select_max_uid(string $denwabango)
  {
    return $this->db->select('MAX(INPUT_DATA_UID) AS INPUT_DATA_UID')
      ->from($this->table_name)
      ->where('denwabango', $denwabango)
      ->get()->result();
  }


  /**
     * 入力データリストの更新処理
     * @param array $data
     * @param array $condition
     * @return int 更新件数
     */
    public function update(array $data, array $condition) : int
    {
        $data['UPDATE_DATETIME'] = date_format(date_create(), 'Y-m-d H:i:s');
        return parent::update($data, $condition);
    }
    
    /**
     * 入力データリストの削除処理
     * @param array $condition
     * @param bool $logical_del_flg 論理削除フラグ
     * @return int
     */
    public function delete(array $condition, bool $logical_del_flg = TRUE) : int
    {
        // 論理削除の場合
        if ($logical_del_flg)
        {
            return $this->update(array('DELETE_FLAG' => DELETE_FLG_DELETED), $condition);
        }
        
        return parent::delete($condition);
    }
    
    /**
     * get_count_of_input_row
     * @param $input_data_uid
     * @return number
     */
    public function get_count_of_input_row($input_data_uid)
    {
        return parent::count(array (
                "INPUT_DATA_UID" => $input_data_uid,
                "CALL_STATUS" => CALL_STATUS_UNDO  // 未処理
        )); 
    }
    
    /**
     * データ件数の取得
     *
     * @param array $condition
     * @param string $search_target
     * @return int
     */
    public function count(array $condition = NULL, string $search_target = NULL): int
    {
        $this->db->from($this->table_name);
        // 検索条件
        if ($condition)
        {
            $this->db->group_start();
            foreach ($condition as $key => $value)
            {
                $this->db->where($key, "$value");
            }
            $this->db->group_end();
        }
        
        // あいまい検索条件
        $this->set_like_condition($search_target);
        
        return $this->db->count_all_results();
    }
    
    /**
     * 入力データ一覧の取得
     * @param int $limit
     * @param int $start
     * @param array $condition
     * @param string $search_target
     * @return array 入力データ一覧
     */
    public function select_page_datas(int $limit, int $start = 0, array $condition = NULL, string $search_target = NULL) : array
    {
            $this->db->select('*')->from($this->table_name);
            // 検索条件
            if ($condition)
            {
                foreach ($condition as $key => $value)
                {
                    $this->db->where($key, "$value");
                }
            }
            
            // あいまい検索条件
            $this->set_like_condition($search_target);
            // order by
            $this->db->order_by('UPDATE_DATETIME', "DESC");
            // limit
            $this->db->limit($limit, $start);
            
            return $this->db->get()->result();
    }
    
    /**
     * あいまい条件の設定
     * @param string $search_target
     */
    private function set_like_condition(string $search_target = NULL)
    {
        if (!empty($search_target))
        {
            $search_list = explode(' ', preg_replace('/¥s|　|、|,+/', ' ', $search_target));
            for ($i = 0; $i < count($search_list); $i++)
            {
                $this->db->group_start();
                $this->db->like('UID', $search_list[$i]);
                $this->db->or_like('denwabango', $search_list[$i]);
                $this->db->or_like('MNPyoyakusyakana', $search_list[$i]);
                $this->db->or_like('MNPyoyakusyakanji', $search_list[$i]);
                $this->db->group_end();
            }
        }
    }
    
    /**
     * @name set_status
     * @desc 「未実行」or「中断」ステータスを設定する。
     * @param array
     * @return int
     */
    public function set_status(array $id_array, $status = "stop") : int
    {
        switch ($status)
        {
            case "stop":
                $data = array (
                        "CALL_STATUS" => CALL_STATUS_STOP 
                );
                $condition = array (
                        "CALL_STATUS" => CALL_STATUS_RESERVE,
                        'DELETE_FLAG' => DELETE_FLG_NOT_DELETE
                );
                break;
            case "restart":
                $data = array (
                        "CALL_STATUS" => CALL_STATUS_RESERVE 
                );
                $condition = array (
                        "CALL_STATUS" => CALL_STATUS_STOP,
                        'DELETE_FLAG' => DELETE_FLG_NOT_DELETE
                );
                break;
            case "reserve":
                $data = array (
                        "CALL_STATUS" => CALL_STATUS_RESERVE 
                );
                $condition = array (
                        "CALL_STATUS" => CALL_STATUS_UNDO,
                        'DELETE_FLAG' => DELETE_FLG_NOT_DELETE
                );
        }
        $loop_count = 0;
        for($i = 0; $i < count($id_array); $i++)
        {
            $condition["INPUT_DATA_UID"] = $id_array[$i];
            $loop_count += $this->update($data, $condition);
        }
        return $loop_count;
    }
    
    /**
     * @name remove
     * @desc 選択された件を削除する。
     * @param array
     * @return int
     */
    public function remove(array $id_array) : int
    {
        $condition = array(
//                 "INPUT_STATUS" => INPUT_STATUS_DONE,
//                 "CALL_STATUS" => CALL_STATUS_DONE
        );
        
        $loop_count = 0;
        for($i=0; $i < count($id_array); $i++)
        {
            $condition["INPUT_DATA_UID"] = $id_array[$i];
            $loop_count += $this->delete($condition, TRUE); // 論理削除
        }
        return $loop_count;
    }

    //--------------------------------------
    /**
     * あいまい条件の設定2
     * @param string $search_target
     */
    private function set_like_condition_4_hankuro_sim(string $search_target = NULL)
    {
        if (!empty($search_target))
        {
            $search_list = explode(' ', preg_replace('/¥s|　|、|,+/', ' ', $search_target));
            for ($i = 0; $i < count($search_list); $i++)
            {
                $this->db->group_start();
                $this->db->like('a.UID', $search_list[$i]);
                $this->db->or_like('c.TENANT_NAME', $search_list[$i]);
                $this->db->or_like('a.denwabango', $search_list[$i]);
                $this->db->or_like('d.seizobango', $search_list[$i]); // SIM_INVENTORY.PRODUCT_NO
                $this->db->or_like('d.sim_status', $search_list[$i]);
                $this->db->group_end();
            }
        }
    }

	/**
	 * データ件数の取得
	 * @desc count_4_open_hankuro_sim
	 * @param array $condition
	 * @param string $search_target
	 * @return int
	 */
	public function count_4_hankuro_sim(array $condition = NULL, string $search_target = NULL): int
	{
		$this->db->select('a.*, c.TENANT_NAME')->from($this->table_name . ' a');
		$this->db->join('INPUT_DATA b', 'a.INPUT_DATA_UID = b.UID');
		$this->db->join('TENANT c', 'b.TENANT_ID = c.TENANT_ID');
		$this->db->join('SIM_INVENTORY d', 'd.SEIZOBANGO = a.SEIZOBANGO'); // 在庫テーブルの外部キー

		// 検索条件
		if ($condition)
		{
			$this->db->group_start();
			foreach ($condition as $key => $value)
			{
				$this->db->where($key, "$value");
			}
			$this->db->group_end();
		}

		// あいまい検索条件
		$this->set_like_condition_4_hankuro_sim($search_target);

		return $this->db->count_all_results();
	}
	/**
	 * 未開通SIM件数の取得
	 * @desc waiting_sim
	 * @param array $condition
	 * @param string $search_target
	 * @return int
	 */
	public function waiting_sim(array $condition = NULL, string $search_target = NULL)
	{
		$this->db->select('a.*')
			->from($this->table_name . ' a');
		$this->db->join('INPUT_DATA b', 'a.INPUT_DATA_UID = b.UID');
		$this->db->join('SIM_INVENTORY d', 'd.SEIZOBANGO = a.SEIZOBANGO'); // 在庫テーブルの外部キー

		// 検索条件
		if ($condition)
		{
			$this->db->group_start();
			foreach ($condition as $key => $value)
			{
				$this->db->where($key, "$value");
			}
			$this->db->group_end();
		}

		// あいまい検索条件
		$this->set_like_condition_4_hankuro_sim($search_target);

		return $this->db->count_all_results();
	}


	/**
     * API開通待ちSIM一覧の取得
     * @param int $limit
     * @param int $start
     * @param array $condition
     * @param string $search_target
     * @param array $order_by
     * @return array 入力データ一リスト覧
     */
    public function select_hankuro_sim(int $limit, int $start = 0, array $condition = NULL, string $search_target = NULL) : array
    {
        $this->db->select('a.*, c.TENANT_NAME, d.SEIZOBANGO, d.SIM_STATUS')->from($this->table_name . ' a');
        $this->db->join('INPUT_DATA b', 'a.INPUT_DATA_UID = b.UID'); 
        $this->db->join('TENANT c', 'b.TENANT_ID = c.TENANT_ID');
        $this->db->join('SIM_INVENTORY d', 'd.SEIZOBANGO = a.SEIZOBANGO'); // 在庫テーブルの外部キー

        // 検索条件
        if ($condition)
        {
            foreach ($condition as $key => $value)
            {
                $this->db->where($key, "$value");
            }
        }
        
        // あいまい検索条件
        $this->set_like_condition_4_hankuro_sim($search_target);
        // オーダー
        $this->db->order_by('a.UPDATE_DATETIME', "DESC");
        // limit
        $this->db->limit($limit, $start);

        return $this->db->get()->result();
    }
    
    /**
     * @name convert_id_to_id
     * @desc INPUT_LIST.UIDをINPUT_DATA.UIDへ変換
     * @desc INPUT_LIST.UID -> INPUT_LIST.INPUT_DATA.UID(INPUT_DATA.UID)
     * @param　string
     * @return string
     */
    public function convert_id($uid) : string
    {
        $row = $this->select_one($uid);
        
        return $row->INPUT_DATA_UID;
    }
    
    /**
     * @name add_m02_hankuro_sim
     * @desc A2のレコードをコピーして、M02のレコードで追加する。
     * @param　array UID
     * @return int 挿入件数
     */
    public function add_m02_4_hankuro_sim(array $id_array)
    {
        $loop_count = 0;
        foreach ($id_array as $id)
        {
            $m02 = $this->select_one($id); // 1件の半黒リスト
            
            // 初期値設定
            $fields = NULL;
            $fields["UID"] = NULL; // auto incrementなので、NULLに設定
            $fields["INPUT_DATA_UID"] = $m02->INPUT_DATA_UID;
            $fields["CALL_STATUS"] = CALL_STATUS_RESERVE;
            $fields["transactionTYPE"] = "M02";
            $fields["denwabango"] = $m02->denwabango;
            
            // MNP転入受付(半黒ROM⇒開通)の場合
            if ($m02->transactionKBN == '2') 
            {
                $fields["transactionKBN"] = '2';
                $fields["MNPyoyakubango"] = $m02->MNPyoyakubango;
                $fields["MNPzokusei"] = $m02->MNPzokusei;
                $fields["MNPyoyakusyakana"] = $m02->MNPyoyakusyakana;
                $fields["MNPyoyakusyakanji"] = $m02->MNPyoyakusyakanji;
                $fields["MNPseinengappi"] = $m02->MNPseinengappi;
            }
            $loop_count += $this->insert($fields); // 累積挿入件数
            
            $this->update(array("HANKURO_SAKUSEI" => 1), array("UID" => $id)); // 半黒作成済
        }
        return $loop_count;
    }
}