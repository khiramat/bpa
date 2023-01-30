<?php

defined('BASEPATH') or exit('No direct script access allowed');

require_once  APPPATH.'/models/Abstract_model.php';

/**
 * 入力データリストテーブル更新用モデル
 */
class Aladin_request_model extends Abstract_model {
    
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
     * @desc ステータス、電話番号及び製造番号の変更
     * @param status
     * @param orderbango
     * @param denwabango
     * @param seizobango
     * @return int
     */
    public function update_input_list(string $status, string $orderbango, string $denwabango, string $seizobango, string $transactionTYPE) : int
    {
        $update_time = date_format(date_create(), 'Y-m-d H:i:s');
        
        if(empty($seizobango)){
            $data = array(
                    'denwabango' => $denwabango,
                    'API_STATUS' => $status,
                    'UPDATE_DATETIME' => $update_time
            );
        }else{
            $data = array(
                    'denwabango' => $denwabango,
                    'SEIZOBANGO' => $seizobango,
                    'API_STATUS' => $status,
                    'UPDATE_DATETIME' => $update_time
            );
        }
        
        if($transactionTYPE == SIM_HB_NEW){
            $data['HANKURO_SAKUSEI'] = '0'; // 半黒作成済み
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
     * @desc 入庫のSIM状態更新
     * @param status
     * @param orderbango
     * @return int
     */
    public function update_sim_inventory(string $seizobango, string $sim_status) : int
    {
        $update_time = date_format(date_create(), 'Y-m-d H:i:s');
        
        $data = array(
                'SIM_STATUS' => $sim_status,
                'UPDATE_DATETIME' => $update_time
        );
        $this->db->where('SEIZOBANGO', "$seizobango");
        return $this->db->update('SIM_INVENTORY', $data) ? $this->db->affected_rows() : 0;
    }
    
    /**
     * @desc ユーザーグループID取得
     * @param orderbango
     * @return string ACCESS_GROUP
     */
    public function call_pcc_get_mvnoid(string $orderbango) : String
    {
        
        $this->db->select('b.TENANT_ID')->from('INPUT_LIST' . ' a');
        $this->db->join('INPUT_DATA b', 'a.INPUT_DATA_UID = b.UID');
        $this->db->where('a.orderbango', "$orderbango");
//         return $this->db->get()->row_array()['TENANT_ID'];
        $tenant_id = $this->db->get()->row_array()['TENANT_ID'];
        
        $this->db->select('ACCESS_GROUP')->from('TENANT');
        $this->db->where('TENANT_ID', $tenant_id);
        
        return $this->db->get()->row_array()['ACCESS_GROUP'];
    }
    
    /**
     * @desc オーダー番号存在チェック
     * @param orderbango
     * @return int
     */
    public function orderbango_count_get(string $orderbango) : int
    {
        $this->db->where('orderbango', "$orderbango");
        return $this->db->count_all_results('INPUT_LIST');
    }
}