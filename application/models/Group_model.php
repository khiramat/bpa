<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once 'Abstract_model.php';

/**
 * テナントマスタ用モデル
 */
class Tenant_model extends Abstract_model {
    
    protected $table_name = 'TENANT';
    
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
     * @param string $tenant_id
     */
    public function select_one(string $tenant_id) {
        $data_list = parent::select_list(array(
                'TENANT_ID' => $tenant_id
        ));
        return empty($data_list) ? NULL : $data_list[0];
    }
    
}