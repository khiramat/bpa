<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once 'Abstract_model.php';

/**
 * SIM在庫表マスタテーブル用モデル
 */
class Sim_inventory_model extends Abstract_model {
    
    protected $table_name = 'SIM_INVENTORY';
    
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
     * 製造番号よって、データの取得処理
     * @param string $seizobango
     */
    public function select_one_by_index(string $seizobango) {
        $data_list = parent::select_list(array(
                'SEIZOBANGO' => $seizobango
        ));
        return empty($data_list) ? NULL : $data_list[0];
    }
    
    /**
     * データの更新処理
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
     * データの削除処理
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
     * データ件数の取得
     *
     * @param array $condition
     * @param string $search_target
     * @return int
     */
    public function count(array $condition = NULL, string $search_target = NULL): int
    {
        $this->db->from($this->table_name . ' a');
        $this->db->join('INPUT_LIST b', 'a.SEIZOBANGO = b.SEIZOBANGO', 'LEFT');
        // 検索条件
        if ($condition)
        {
            $this->db->group_start();
            foreach ($condition as $key => $value)
            {
                $this->db->where('a.' . $key, "$value");
            }
            $this->db->group_end();
        }
        
        // あいまい検索条件
        $this->set_like_condition($search_target);
        return $this->db->count_all_results();
    }
    
    /**
     * データ一覧の取得
     * @param int $limit
     * @param int $start
     * @param array $condition
     * @param string $search_target
     * @return array データ一覧
     */
    public function select_page_datas(int $limit, int $start = 0, array $condition = NULL, string $search_target = NULL) : array
    {
        $this->db->select('b.*,a.*')->from($this->table_name . " a");
        $this->db->join('INPUT_LIST b', 'a.SEIZOBANGO = b.SEIZOBANGO', 'LEFT');
        
        // 検索条件
        if ($condition)
        {
            foreach ($condition as $key => $value)
            {
                $this->db->where('a.' . $key, "$value");
            }
        }
        
        // あいまい検索条件
        $this->set_like_condition($search_target);
        // オーダー
        $this->db->order_by('a.UPDATE_DATETIME', "DESC");
        // limit
        $this->db->limit($limit, $start);
        
        return $this->db->get()->result();
    }
    
    /**
     * 統計情報の取得
     * @param array $condition
     * @param string $search_target
     * @return array
     */
    public function select_statistics(array $condition = NULL, string $search_target = NULL) : array
    {
        $this->db->select('LEFT(SEIZOBANGO, 2) as MAKER, SIM_TYPE, SHIPMENT_FLAG, COUNT(SIM_TYPE) AS CNT')
                 ->from($this->table_name . ' a');
        // 検索条件
        if ($condition)
        {
            foreach ($condition as $key => $value)
            {
                $this->db->where($key, "$value");
            }
        }
        
        // あいまい検索条件
        $this->set_like_condition($search_target, FALSE);
        
        // group by
        $this->db->group_by(array('MAKER', 'SIM_TYPE', 'SHIPMENT_FLAG'));
        
        return $this->edit_statistics($this->db->get()->result());
    }
    
    /**
     * 統計情報の計算
     * @param array $sim_list
     * @return array
     */
    private function edit_statistics(array $sim_list) : array
    {
        $result = array();
        if (empty($sim_list))
        {
            return $result;
        }
        
        foreach ($sim_list as $sim_info)
        {
            if (!isset($result[$sim_info->MAKER]))
            {
                // 初期化
                $result[$sim_info->MAKER] = array(
                        'MAKER' => $sim_info->MAKER,
                        'SIM_ALL' => 0,
                        'SIM_IN_STOCK' => 0,
                        'MINI_ALL' => 0,
                        'MINI_IN_STOCK' => 0,
                        'MULTI_ALL' => 0,
                        'MULTI_IN_STOCK' => 0,
                        'NANO_ALL' => 0,
                        'NANO_IN_STOCK' => 0,
                        'TOTAL' => 0,
                        'TOTAL_IN_STOCK' => 0
                );
            }
            
            // 小計
            $result[$sim_info->MAKER]['TOTAL'] += $sim_info->CNT;
            
            // 未発送の場合
            if ($sim_info->SHIPMENT_FLAG == SHIPMENT_UNDO)
            {
                // 小計
                $result[$sim_info->MAKER]['TOTAL_IN_STOCK'] += $sim_info->CNT;
                
                // 標準
                if ($sim_info->SIM_TYPE == SIM_TYPE_SIM)
                {
                    $result[$sim_info->MAKER]['SIM_ALL'] += $sim_info->CNT;
                    $result[$sim_info->MAKER]['SIM_IN_STOCK'] += $sim_info->CNT;
                }
                // micro
                elseif ($sim_info->SIM_TYPE == SIM_TYPE_MICRO)
                {
                    $result[$sim_info->MAKER]['MINI_ALL'] += $sim_info->CNT;
                    $result[$sim_info->MAKER]['MINI_IN_STOCK'] += $sim_info->CNT;
                }
                // マルチ
                elseif ($sim_info->SIM_TYPE == SIM_TYPE_MULTI)
                {
                    $result[$sim_info->MAKER]['MULTI_ALL'] += $sim_info->CNT;
                    $result[$sim_info->MAKER]['MULTI_IN_STOCK'] += $sim_info->CNT;
                }
                // nano
                else
                {
                    $result[$sim_info->MAKER]['NANO_ALL'] += $sim_info->CNT;
                    $result[$sim_info->MAKER]['NANO_IN_STOCK'] += $sim_info->CNT;
                }
            }
            else
            {
                // 標準
                if ($sim_info->SIM_TYPE == SIM_TYPE_SIM)
                {
                    $result[$sim_info->MAKER]['SIM_ALL'] += $sim_info->CNT;
                }
                // micro
                elseif ($sim_info->SIM_TYPE == SIM_TYPE_MICRO)
                {
                    $result[$sim_info->MAKER]['MINI_ALL'] += $sim_info->CNT;
                }
                // マルチ
                elseif ($sim_info->SIM_TYPE == SIM_TYPE_MULTI)
                {
                    $result[$sim_info->MAKER]['MULTI_ALL'] += $sim_info->CNT;
                }
                // nano
                else
                {
                    $result[$sim_info->MAKER]['NANO_ALL'] += $sim_info->CNT;
                }
            }
        }
        
        // sort
        foreach ($result as $key => $value) {
            $sort[$key] = $value['TOTAL'];
        }
        array_multisort($sort, SORT_DESC, $result);
        
        return array_values($result);
    }
    
    /**
     * ダウンロード用データの取得
     * @param array $condition
     * @param string $search_target
     * @param array $uid_list
     * @return array
     */
    public function select_download_datas(array $condition = NULL, string $search_target = NULL, array $uid_list = NULL) : array
    {
        $this->db->select('b.*,a.*')->from($this->table_name . " a");
        $this->db->join('INPUT_LIST b', 'a.SEIZOBANGO = b.SEIZOBANGO', 'LEFT');
        
        // 検索条件
        if ($condition)
        {
            foreach ($condition as $key => $value)
            {
                $this->db->where('a.' . $key, "$value");
            }
        }
        
        // あいまい検索条件
        $this->set_like_condition($search_target);
        
        // in id list
        if ($uid_list) {
            $this->db->where_in('a.UID', $uid_list);
        }
        
        // オーダー
        $this->db->order_by('a.UPDATE_DATETIME', "DESC");
        
        return $this->db->get()->result();
    }
    
    /**
     * あいまい条件の設定
     * @param string $search_target
     */
    private function set_like_condition(string $search_target = NULL, bool $with_join = TRUE)
    {
        if (!empty($search_target))
        {
            $search_list = explode(' ', preg_replace('/¥s|　|、|,+/', ' ', $search_target));
            for ($i = 0; $i < count($search_list); $i++)
            {
                $this->db->group_start();
                $this->db->like('a.UID', $search_list[$i]);
                $this->db->or_like('a.SEIZOBANGO', $search_list[$i]);
                $this->db->or_like('a.SHIPMENT_DEST', $search_list[$i]);
                $this->db->or_like('a.SHIPMENT_DEST2', $search_list[$i]);
                $this->db->or_like('a.MVNO_ID', $search_list[$i]);
                $this->db->or_like('a.POOL_ID', $search_list[$i]);
                if ($with_join) 
                {
                    $this->db->or_like('b.denwabango', $search_list[$i]);
                    $this->db->or_like('b.ansyobango', $search_list[$i]);
                }
                if (preg_match('/^([\d\-\/]+)$/', $search_list[$i]) != FALSE)
                {
                    $this->db->or_like('a.ARRIVAL_DATETIME', str_replace('/', '-', $search_list[$i]));
                    $this->db->or_like('a.SHIPMENT_DATETIME', str_replace('/', '-', $search_list[$i]));
                    $this->db->or_like('a.DELIVERY_DATE', str_replace('/', '-', $search_list[$i]));
                    $this->db->or_like('a.RS_RETURN_DATE', str_replace('/', '-', $search_list[$i]));
                    $this->db->or_like('a.DOCOMO_RESULT_REQ_DATE', str_replace('/', '-', $search_list[$i]));
                }
                $this->db->group_end();
            }
        }
    }
    
}