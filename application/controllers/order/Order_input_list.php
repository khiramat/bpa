<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * オーダー入力一覧用コントローラー
 */
class Order_input_list extends CI_Controller {
    
    /**
     * 入力データリスト一覧
     * @param string $input_data_uid 入力データシーケンスID
     * @param int $cur_page
     */
    public function index(string $input_data_uid = NULL, int $cur_page = 1)
    {
        if (empty($input_data_uid))
        {
            $this->load->view('order/order_input_list');
            return;
        }
        
        $this->load->library('pagination');
        $this->load->helper('property');
        $this->load->model('input_data_model');
        $this->load->model('input_list_model');
        
        // 入力データの存在チェック
        $input_data = $this->input_data_model->select_one($input_data_uid);
        if ($input_data == null)
        {
            $this->load->view('order/order_input_list');
            return;
        }
        
        // 1ページで表示件数
        if ($this->session->has_userdata('order_order_input_list_per_page'))
        {
            $per_page = $this->session->userdata('order_order_input_list_per_page');
        }
        else
        {
            $per_page = array_keys(get_select_property('page_size'))[0];
        }
        
        // 検索条件の作成
        if ($this->session->has_userdata('order_order_input_list_search_condition'))
        {
            $search_condition = $this->session->userdata('order_order_input_list_search_condition');
        }
        $search_condition['INPUT_DATA_UID'] = $input_data_uid;      // 入力データシーケンスID
        $search_condition['DELETE_FLAG'] = DELETE_FLG_NOT_DELETE;   // 未削除
        
        // あいまい検索条件
        if ($this->session->has_userdata('order_order_input_list_search_target'))
        {
            $search_target = $this->session->userdata('order_order_input_list_search_target');
        }
        else
        {
            $search_target = NULL;
        }
        
        // 総件数の取得
        $total_rows = $this->input_list_model->count($search_condition, $search_target);
        
        if ($total_rows > 0)
        {
            // current page
            $max_page = ceil($total_rows / $per_page);
            if ($cur_page > $max_page)
            {
                $cur_page = $max_page;
            }
            
            // pagination初期化
            $config['base_url'] = site_url('order/order_input_list/'. $input_data_uid);
            $config['total_rows'] = $total_rows;
            $config['per_page'] = $per_page;
            $config['uri_segment'] = 4;
            $this->pagination->initialize($config);
            
            // ページデータの取得
            $input_list = $this->input_list_model->select_page_datas($per_page,
                ($cur_page - 1) * $per_page, $search_condition, $search_target);
            
            // 返却結果
            $data['page_links'] = $this->pagination->create_links();
            $data['input_list'] = $input_list;
        }
        
        // 返却結果
        $data['input_data_info'] = $input_data;
        $data['INPUT_DATA_UID'] = $input_data_uid;
        $data['cur_page'] = $cur_page;
        $data['per_page'] = $per_page;
        $data['total_rows'] = $total_rows;
        $data['search_target'] = $search_target;
        if (!empty($search_condition))
        {
            // トランザクションタイプ
            $data['transactionTYPE'] = isset($search_condition['transactionTYPE']) ? $search_condition['transactionTYPE'] : '';
            // カード形状
            $data['cardkeijo'] = isset($search_condition['cardkeijo']) ? $search_condition['cardkeijo'] : '';
            // コールステータス
            $data['call_status'] = isset($search_condition['CALL_STATUS']) ? $search_condition['CALL_STATUS'] : '';
        }
        else
        {
            // トランザクションタイプ
            $data['transactionTYPE'] = '';
            // カード形状
            $data['cardkeijo'] = '';
            // 実行ステータス
            $data['call_status'] = '';
        }
        
        $this->load->view('order/order_input_list', $data);
    }
    
    /**
     * 検索処理
     */
    public function do_search()
    {
        $condition = NULL;
        // トランザクションタイプ
        $transactionTYPE = htmlspecialchars(trim($this->input->post('transactionTYPE', TRUE)));
        if ($transactionTYPE !== '')
        {
            $condition['transactionTYPE'] = $transactionTYPE;
        }
        // カード形状
        $cardkeijo = htmlspecialchars(trim($this->input->post('cardkeijo', TRUE)));
        if ($cardkeijo !== '')
        {
            $condition['cardkeijo'] = $cardkeijo;
        }
        // 実行ステータス
        $call_status = htmlspecialchars(trim($this->input->post('call_status', TRUE)));
        if ($call_status !== '')
        {
            $condition['CALL_STATUS'] = $call_status;
        }
        // set session
        if ($condition != NULL)
        {
            $this->session->set_userdata('order_order_input_list_search_condition', $condition);
        }
        else
        {
            $this->session->unset_userdata('order_order_input_list_search_condition');
        }
        
        // 検索項目
        $search_target = htmlspecialchars(trim($this->input->post('search_target', TRUE)));
        if (!empty($search_target))
        {
            $this->session->set_userdata('order_order_input_list_search_target', $search_target);
        }
        else
        {
            $this->session->unset_userdata('order_order_input_list_search_target');
        }
        
        // 入力リスト一覧画面の表示
        $input_data_uid = htmlspecialchars(trim($this->input->post('INPUT_DATA_UID', TRUE)));
        redirect('order/order_input_list/' . $input_data_uid, 'location');
    }
    
    /**
     * ページサイズ変更処理
     */
    public function set_page_size()
    {
        $this->load->helper('property');
        
        $per_page = htmlspecialchars(trim($this->input->post('per_page', TRUE)));
        $page_size = array_keys(get_select_property('page_size'));
        if (!in_array($per_page, $page_size))
        {
            $per_page = $page_size[0];
        }
        $this->session->set_userdata('order_order_input_list_per_page', $per_page);
        
        // 入力リスト一覧画面の表示
        $input_data_uid = htmlspecialchars(trim($this->input->post('INPUT_DATA_UID', TRUE)));
        redirect('order/order_input_list/' . $input_data_uid, 'location');
    }
    
    /**
     * データ削除処理
     * @param string $uid
     */
    public function remove()
    {
        // ajax check
        if (!$this->input->is_ajax_request() || $this->input->method() != 'post')
        {
            die('Access denied');
        }
        
        $this->load->model('input_list_model');
        
        // パラメータ取得
        $uid_list = $this->input->post('uid_list', TRUE);
        
        // ================= 削除前のチェック ================= //
        // 単体チェック
        if (empty($uid_list))
        {
            echo json_encode(array(
                    'success'=> false,
                    'message'=> '削除対象が見つかりませんでした。'
            ));
            exit();
        }
        
        $result_details = NULL;
        $success_cnt = 0;
        $failed_cnt = 0;
        
        foreach ($uid_list as $uid)
        {
            // データ存在チェック
            $input_list_data = $this->input_list_model->select_one($uid);
            if ($input_list_data == NULL)
            {
                $result_details[$uid] = array(
                        'success'=> false,
                        'message'=> '削除対象が見つかりませんでした。'
                );
                $failed_cnt++;
                continue;
            }
            
            // コールステータスチェック[実行中/実行完了のデータは、削除不可]
//             if ($input_list_data->CALL_STATUS == CALL_STATUS_DOING or $input_list_data->CALL_STATUS == CALL_STATUS_DONE)
//             {
//                 $result_details[$uid] = array(
//                         'success'=> false,
//                         'message'=> '未実行/中断のデータのみ、削除可能。'
//                 );
//                 $failed_cnt++;
//                 continue;
//             }
            
            // ================= 削除処理を行う ================= //
            if ($this->input_list_model->delete(array('UID' => $uid)) != 1)
            {
                $result_details[$uid] = array(
                        'success'=> false,
                        'message'=> '削除処理が失敗しました。'
                );
                $failed_cnt++;
                continue;
            }
            
            // TODO 入力データ枚数の変更 ?
            $result_details[$uid] = array(
                    'success'=> TRUE
            );
            $success_cnt++;
        }
        
        echo json_encode(array(
                'success' => $failed_cnt == 0,
                'details' => $result_details,
                'success_cnt' => $success_cnt,
                'failed_cnt' => $failed_cnt
        ));
    }
    
}