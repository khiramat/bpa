<?php
defined('BASEPATH') or exit('No direct script access allowed');

require_once  APPPATH.'/models/Abstract_model.php';

/**
 * 入力データリストテーブル更新用モデル
 */
class Acs_request_model extends Abstract_model {
    
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
	 * @desc オーダー番号存在確認
	 * @param status
	 * @param orderbango
	 * @param denwabango
	 * @return int
	 */
	public function orderbango_chk(string $orderbango) : int
	{
		
		$this->db->where('orderbango', "$orderbango");
		return $this->db->count_all_results('INPUT_LIST');
	}
	
	/**
	 * @desc 電話番号存在確認
	 * @param status
	 * @param orderbango
	 * @param denwabango
	 * @return int
	 */
	public function denwabango_chk(string $orderbango)
	{
		$this->db->select('*')
		         ->from($this->table_name)
		         ->where('orderbango', "$orderbango");
		return $this->db->get()->result();
	}
	
	
	/**
	 * @desc 電話番号存在の場合
	 * @param status
	 * @param orderbango
	 * @param denwabango
	 * @return int
	 */
	public function overlapping($denwabango_chk, $denwabango)
	{
		// 初期値設定
		$fields = NULL;
		$fields["UID"] = NULL; // auto incrementなので、NULLに設定
		$fields["INPUT_DATA_UID"] = $denwabango_chk -> INPUT_DATA_UID;
		$fields["CALL_STATUS"] = $denwabango_chk -> CALL_STATUS;
		$fields["transactionTYPE"] = $denwabango_chk -> transactionTYPE;
		$fields["POOL_GROUP"] = $denwabango_chk -> POOL_GROUP;
		$fields["cardkeijo"] = $denwabango_chk -> cardkeijo;
		$fields["ansyobango"] = $denwabango_chk -> ansyobango;
		$fields["ryokinplan"] = $denwabango_chk -> ryokinplan;
		$fields["contracttype"] = $denwabango_chk -> contracttype;
		$fields["sousaservice"] = $denwabango_chk -> sousaservice;
		$fields["CALL_STATUS"] = $denwabango_chk -> CALL_STATUS;
		$fields["HANKURO_SAKUSEI"] = $denwabango_chk -> HANKURO_SAKUSEI;
		$fields["API_STATUS"] = 2;
		$fields["API_CODE"] = $denwabango_chk -> API_CODE;
		$fields["ACS_RESULT"] = $denwabango_chk -> ACS_RESULT;
		$fields["CREATE_DATETIME"] = $denwabango_chk -> CREATE_DATETIME;
		$fields["DELETE_FLAG"] = 0;
		$fields["denwabango"] = $denwabango;
		$fields["orderbango"] = '999999999999999';
		$overlapping_count = $this->insert($fields);
		return $overlapping_count;
	}
	
	
	/**
     * @desc ステータスおよび電話番号の更新
     * @param status
     * @param orderbango
     * @param denwabango
     * @return int
     */
    public function update_input_list(string $status, string $orderbango, string $denwabango) : int
    {
        //$data['UPDATE_DATETIME'] = date_format(date_create(), 'Y-m-d H:i:s');
        $update_time = date_format(date_create(), 'Y-m-d H:i:s');
        
        if (!empty($denwabango)){
            $data = array(
                    'denwabango' => $denwabango,
                    'API_STATUS' => $status,
                    'UPDATE_DATETIME' => $update_time
            );
        }else{
            $data = array(
                    'API_STATUS' => $status,
                    'UPDATE_DATETIME' => $update_time
            );
        }

        $condition = array(
                'orderbango' => $orderbango
        );
        return parent::update($data, $condition);
    }
    /**
     * @desc ステータスエラー更新
     * @param status
     * @param orderbango
     * @return int
     */
    public function update_input_list_error(string $status, string $orderbango) : int
    {
        $update_time = date_format(date_create(), 'Y-m-d H:i:s');
        
        $data = array(
                'API_STATUS' => $status,
                'UPDATE_DATETIME' => $update_time
        );
        
        $condition = array(
                'orderbango' => $orderbango
        );
        return parent::update($data, $condition);
    }
    
    /**
     * @desc カード再発行フラグ判定
     * @param orderbango
     * @return int
     */
    
    public function cardsaihakoFLG_chk(string $orderbango) : int
    {
        $this->db->where('cardsaihakoFLG', "1");
        $this->db->where('orderbango', "$orderbango");
        return $this->db->count_all_results('INPUT_LIST');
    }
    /**
     * @desc ステータスエラー更新
     * @param status
     * @param orderbango
     * @return int
     */
    public function update_input_list_error_code(string $orderbango, string $syorikekkaKbn) : int
    {
        $update_time = date_format(date_create(), 'Y-m-d H:i:s');
        
        $data = array(
                'API_CODE' => $syorikekkaKbn,
                'API_STATUS' => API_STATUS_REQUEST_ERROR,
                'UPDATE_DATETIME' => $update_time
        );
        
        $condition = array(
                'orderbango' => $orderbango
        );
        return parent::update($data, $condition);
    }
}

    