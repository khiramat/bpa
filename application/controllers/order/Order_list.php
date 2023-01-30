<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * API新規オーダー一覧用コントローラー
 */
class Order_list extends CI_Controller {
    
    /**
     * 新規オーダー一覧
     */
    public function index($cur_page = 1)
    {
        //$this->output->enable_profiler(TRUE);
        
        $this->load->library('pagination');
        $this->load->helper('property');
        $this->load->model('input_data_model');
        
        // 1ページで表示件数
        if ($this->session->has_userdata('order_order_list_per_page'))
        {
            $per_page = $this->session->userdata('order_order_list_per_page');
        }
        else
        {
            $per_page = array_keys(get_select_property('page_size'))[0];
        }

        // 検索条件の作成
        if ($this->session->has_userdata('order_order_list_search_condition'))
        {
            $search_condition = $this->session->userdata('order_order_list_search_condition');
        }
        $search_condition['DELETE_FLAG'] = DELETE_FLG_NOT_DELETE;   // 未削除
        
        // あいまい検索条件
        if ($this->session->has_userdata('order_order_list_search_target'))
        {
            $search_target = $this->session->userdata('order_order_list_search_target');
        }
        else
        {
            $search_target = NULL;
        }
        
        // 総件数の取得
        $total_rows = $this->input_data_model->count($search_condition, $search_target);
        
        if ($total_rows > 0)
        {
            // current page
            $max_page = ceil($total_rows / $per_page);
            if ($cur_page > $max_page)
            {
                $cur_page = $max_page;
            }
            
            // pagination初期化
            $config['base_url'] = site_url('order/order_list');
            $config['total_rows'] = $total_rows;
            $config['per_page'] = $per_page;
            $this->pagination->initialize($config);
            
            // ページデータの取得
            $input_data = $this->input_data_model->select_page_datas($per_page,
                ($cur_page - 1) * $per_page, $search_condition, $search_target);
            
            // 返却結果
            $data['page_links'] = $this->pagination->create_links();
            $data['input_data'] = $input_data;
        }
        
        // 返却結果
        $data['cur_page'] = $cur_page;
        $data['per_page'] = $per_page;
        $data['total_rows'] = $total_rows;
        $data['search_target'] = $search_target;
        if (!empty($search_condition))
        {
            // 検索日付(始)
            if (isset($search_condition['UPDATE_DATETIME >='])) 
            {
                $data['time_from'] = date('Ymd', strtotime($search_condition['UPDATE_DATETIME >=']));
            }
            else 
            {
                $data['time_from'] = '';
            }
            // 検索日付(終)
            if (isset($search_condition['UPDATE_DATETIME <=']))
            {
                $data['time_to'] = date('Ymd', strtotime($search_condition['UPDATE_DATETIME <=']));
            }
            else
            {
                $data['time_to'] = '';
            }
            // トランザクションタイプ
            $data['transactionTYPE'] = isset($search_condition['transactionTYPE']) ? $search_condition['transactionTYPE'] : '';
            // カード形状
            $data['cardkeijo'] = isset($search_condition['cardkeijo']) ? $search_condition['cardkeijo'] : '';
            // 入力ステータス
            $data['input_status'] = isset($search_condition['INPUT_STATUS']) ? $search_condition['INPUT_STATUS'] : '';
            // コールステータス
            $data['call_status'] = isset($search_condition['CALL_STATUS']) ? $search_condition['CALL_STATUS'] : '';
        }
        else
        {
            // 検索日付(始)
            $data['time_from'] = '';
            // 検索日付(終)
            $data['time_to'] = '';
            // トランザクションタイプ
            $data['transactionTYPE'] = '';
            // カード形状
            $data['cardkeijo'] = '';
            // 入力ステータス
            $data['input_status'] = '';
            // 実行ステータス
            $data['call_status'] = '';
        }
        
        $this->load->view('order/order_list', $data);
    }
    
    /**
     * 検索処理
     */
    public function do_search()
    {
        $condition = NULL;
        // 検索日付 (始)
        $time_from = htmlspecialchars(trim($this->input->post('time_from', TRUE)));
        if ($time_from !== '' and $this->form_validation->valid_date($time_from, 'Y/m/d'))
        {
            $condition['UPDATE_DATETIME >='] = date('Y-m-d H:i:s', strtotime($time_from));
        }
        // 検索日付 (終)
        $time_to = htmlspecialchars(trim($this->input->post('time_to', TRUE)));
        if ($time_to !== '' and $this->form_validation->valid_date($time_to, 'Y/m/d'))
        {
            $condition['UPDATE_DATETIME <='] = date('Y-m-d H:i:s', strtotime($time_to . '235959'));
        }
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
        // 入力ステータス
        $input_status = htmlspecialchars(trim($this->input->post('input_status', TRUE)));
        if ($input_status !== '')
        {
            $condition['INPUT_STATUS'] = $input_status;
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
            $this->session->set_userdata('order_order_list_search_condition', $condition);
        }
        else
        {
            $this->session->unset_userdata('order_order_list_search_condition');
        }
        
        // 検索項目
        $search_target = htmlspecialchars(trim($this->input->post('search_target', TRUE)));
        if (!empty($search_target))
        {
            $this->session->set_userdata('order_order_list_search_target', $search_target);
        }
        else
        {
            $this->session->unset_userdata('order_order_list_search_target');
        }
        
        // 新規オーダー一覧画面の表示
        redirect('order/order_list', 'location');
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
        $this->session->set_userdata('order_order_list_per_page', $per_page);
        
        // 新規オーダー一覧画面の表示
        redirect('order/order_list', 'location');
    }
    
    /**
     * @name reserve
     * @desc acs側への呼出しを「実行」する。
     * @desc 画面一覧で、実行ボタンが押されたら、当該の件(レコード)のコールステータスを「実行中」に設定する
     * @param array
     * @return void
     */
    public function reserve()
    {
        $this->load->model('input_data_model');
        $this->load->model('input_list_model');
        //change stop flag
        $ids = $this->input->post("IDs"); // get Ajax-post-data by「実行」ボタン from view
        
        if(empty($ids))
        {
            return; // 選択件がない場合
        }
        $id_array = explode(',', $ids);
        
        $this->input_data_model->set_status($id_array, "reserve");
        $count = $this->input_list_model->set_status($id_array, "reserve");
        
        echo json_encode($count);
    }
    
    /**
     * @name stop_running
     * @desc acs側への呼出しを「中断」する。
     * @desc 画面一覧で、中断ボタンが押されたら、当該の件(レコード)のコールステータスを「中断」に設定する
     * @param array
     * @return void
     */
    public function stop()
    {
        $this->load->model('input_data_model');
        $this->load->model('input_list_model');
        //change stop flag
        $ids = $this->input->post("IDs"); // get Ajax-post-data by「中断」ボタン on view
        
        if(empty($ids))
        {
            return;
        }
        $id_array = explode(',', $ids);
        
        $count = $this->input_data_model->set_status($id_array, "stop"); // input_data table
//         $count = $this->input_list_model->set_status($id_array, "stop"); // input_list table
        
        echo json_encode($count);
    }
    
    /**
     * @name restart
     * @desc acs側への呼出しを「再開」する。
     * @desc 画面一覧で、再開ボタンが押されたら、当該の件(レコード)のコールステータスを「未実行」に設定する
     * @param array
     * @return void
     */
    public function restart()
    {
        $this->load->model('input_data_model');
        $this->load->model('input_list_model');
        //change stop flag
        $ids = $this->input->post("IDs"); // get Ajax-post-data by「再開」ボタン from view
        
        if(empty($ids))
        {
            return; // 選択件がない場合
        }
        $id_array = explode(',', $ids);
        
        $count = $this->input_data_model->set_status($id_array, "restart");
        $this->input_list_model->set_status($id_array, "restart");
        
        echo json_encode($count);
    }
    
    /**
     * @name remove
     * @desc 選択された件を削除する(論理削除)
     * @desc 画面一覧で、削除ボタンが押されたら、当該の件(レコード)を削除する
     * @param array
     * @return void
     */
    public function remove()
    {
        $this->load->model('input_data_model');
        $this->load->model('input_list_model');
        //change stop flag
        $ids = $this->input->post("IDs"); // get Ajax-post-data by「実行」ボタン from view
        
        if(empty($ids))
        {
            return; // 選択件がない場合
        }
        $id_array = explode(',', $ids);

        $this->input_data_model->remove($id_array);
        $count = $this->input_list_model->remove($id_array);
        
        echo json_encode($count);
    }
    
//     function destroy()
//     {
//         $this->session->sess_destroy();
//     }
    
}