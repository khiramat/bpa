<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * アブストラクトモデル
 */
abstract class Abstract_model extends CI_Model {
    
    /**
     * テーブル名
     * @var string
     */
    protected $table_name;

    /**
     * コンストラクタ
     */
    public function __construct(string $table_name)
    {
        $this->table_name = $table_name;
        parent::__construct();
    }

    /**
     * データリストの取得
     * 
     * @param array $condition
     * @return array
     */
    public function select_list(array $condition = NULL, array $order_by = NULL): array
    {
        $this->db->select('*')->from($this->table_name);
        if ($condition)
        {
            foreach ($condition as $key => $value)
            {
                $this->db->where($key, "$value");
            }
        }
        if ($order_by)
        {
            foreach ($order_by as $order_target => $order)
            {
                $this->db->order_by($order_target, $order);
            }
        }
        return $this->db->get()->result();
    }

    /**
     * データ件数の取得
     * 
     * @param array $condition
     * @return int
     */
    public function count(array $condition = NULL): int
    {
        $this->db->from($this->table_name);
        if ($condition)
        {
            foreach ($condition as $key => $value)
            {
                $this->db->where($key, "$value");
            }
        }
        return $this->db->count_all_results();
    }

    /**
     * データの挿入処理
     * 
     * @param array $data
     * @return int 挿入件数
     */
    public function insert(array $data): int
    {
        return $this->db->insert($this->table_name, $data) ? 1 : 0;
    }

    /**
     * データの更新処理
     * 
     * @param array $data
     * @param array $condition
     * @return int 更新件数
     */
    public function update(array $data, array $condition): int
    {
        if ($condition)
        {
            foreach ($condition as $key => $value)
            {
                $this->db->where($key, "$value");
            }
        }
        return $this->db->update($this->table_name, $data) ? $this->db->affected_rows() : 0;
    }

    /**
     * データの削除処理
     * 
     * @param array $condition
     * @return int 削除件数
     */
    public function delete(array $condition): int
    {
        if ($condition)
        {
            foreach ($condition as $key => $value)
            {
                $this->db->where($key, "$value");
            }
        }
        return $this->db->delete($this->table_name) ? $this->db->affected_rows() : 0;
    }
}