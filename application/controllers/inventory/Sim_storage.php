<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * SIM入庫用コントローラー
 */
class Sim_storage extends CI_Controller {
    
    /**
     * SIM入庫画面
     */
    public function index()
    {
        $this->load->helper('property');
        $this->load->view('inventory/sim_storage');
    }
    
    
    /**
     * 登録処理
     */
    public function save()
    {
        if (!$this->input->is_ajax_request() || $this->input->method() != 'post')
        {
            die('Access denied');
        }
        
        $this->load->model('sim_inventory_model');
        
        // フォームバリデーション
        if ($this->form_validation->run('inventory/sim_storage/save') == FALSE)
        {
            echo json_encode(array(
                    'success'=> FALSE,
                    'message'=> validation_errors()
            ));
            exit();
        }
        
        // SIMタイプ
        $sim_type = $this->input->post('SIM_TYPE');
        // 読込結果(製造番号リスト)
        $seizobango_list = array_unique(explode(',', $this->input->post('SEIZOBANGO_STR')));
        
        $failed_cnt = 0;        // 失敗件数
        $success_cnt = 0;       // 成功件数
        $result_details = NULL;
        foreach ($seizobango_list as $seizobango)
        {
            // 製造番号のチェック
            if (!preg_match('/^[a-zA-Z]{2}[0-9]{13}$/', $seizobango))
            {
                $result_details[$seizobango] = array(
                        'success'=> false,
                        'message'=> '製造番号が正しくありません。'
                );
                $failed_cnt++;
                continue;
            }
            
            // 登録処理を行う
            $cnt = $this->sim_inventory_model->insert(array(
                    'SEIZOBANGO' => $seizobango,            // 製造番号
                    'SIM_SUPPLIER' => '0',                  // 仕入先: docomo
                    'SIM_TYPE' => $sim_type,                // SIMタイプ
                    'SIM_STATUS' => '0',                    // SIM状態
            ));
            if ($cnt > 0)
            {
                $result_details[$seizobango] = array(
                        'success'=> TRUE
                );
                $success_cnt++;
            }
            else
            {
                $result_details[$seizobango] = array(
                        'success'=> false,
                        'message'=> '登録処理が失敗しました。'
                );
                $failed_cnt++;
            }
        }
        
        echo json_encode(array(
                'success' => $failed_cnt == 0,
                'message' => $failed_cnt > 0 ? '登録件数: ' . $success_cnt . ', 失敗件数: ' . $failed_cnt : '',
                'details' => $result_details,
        ));
    }
    
}