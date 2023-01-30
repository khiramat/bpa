<?php
defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

/**
 * aladin 登録要求用コントローラー
 */
class AladinRequestHandler extends \Restserver\Libraries\REST_Controller {
    
    /**
     * @name AladinRequestHandler
     * @desc ALADIN 登録要求 api
     * @param array
     * @return 正常:200
     */
    public function index_post()
    {
        // model load
        $this->load->model('input_data_model');
        $this->load->model('input_list_model');
        $this->load->model('api/v1/dcm/Aladin_request_model');
        $this->load->helper('utility');
        
        // 通信方向
        $if_direction = "ALADIN->BPA";
        
        //  I/F名称
        $api_name = "bpa-api";
        
        // HTTP処理結果コード
        $http_response_code = HTTP_OK;
        
        // ENTRYログ
        $logmsg = $api_name . ',' .$if_direction . "処理が開始します。";
        log_message('info', $logmsg);
        
        // リクエストヘッダチェック
        // ip check todo
        //$headers = getallheaders();
        //var_dump($headers);
        
        // POSTデータ取得
        $post_data = $this->post_data_set();
        
        // バリデーションチェック
        $validate_flg = $this->validate($post_data['transactionTYPE'],$post_data['orderbango'],$post_data['denwabango']);
        
        if($validate_flg ==  RESULT_OK ){
            //処理結果区分判定
            switch ($post_data['transactionTYPE']){
                // 新規申込
                case SIM_NEW :
                    // 新規申込(半黒)
                case SIM_HB_NEW :
                    // 既設変更
                case SIM_CHANGE :
                    // 完了状態
                    $up_status = API_STATUS_RESPONSE_OK;
                    // オーダー番号
                    $up_orderbango = $post_data['orderbango'];
                    // 電話番号
                    $up_denwabango = $post_data['denwabango'];
                    // 製造番号
                    $up_seizobango = $post_data['seizobango'];
                    
                    $count = $this->Aladin_request_model->orderbango_count_get($post_data['orderbango']);
                    if( $count > 0 ){
                        // 処理なし
                    }else{
                        $logmsg = $api_name . ',' .$if_direction . "：オーダー番号なし、処理が完了しました。";
                        log_message('info', $logmsg);
                        http_response_code( HTTP_OK );
                        exit ;
                    }
                    
                    // INPUT LIST TBL更新処理
                    $logmsg = "INPUTLISTテーブルの更新処理を開始します。";
                    log_message('info', $logmsg);
                    $update_count = $this->Aladin_request_model->update_input_list($up_status, $up_orderbango, $up_denwabango, $up_seizobango, $post_data['transactionTYPE']);
                    $logmsg = "INPUTLISTテーブルの更新処理が完了しました。".'$update_count['.$update_count.']';
                    log_message('info', $logmsg);
                    if ($update_count > 0){
                      $http_response_code = HTTP_OK;
                      // 入庫SIM状態の更新
                      if(!empty($post_data['seizobango'])){
                        $logmsg = "入庫SIM状態の更新処理が開始します。";
                        log_message('info', $logmsg);
                        $update_sim_inventory_count = $this -> Aladin_request_model -> update_sim_inventory($post_data['seizobango'], SIM_STATUS_DONE);
                        $logmsg                     = "入庫SIM状態の更新処理が完了しました。";
                        log_message('info', $logmsg);
                      }

                      // pccサーバーの連携
                      $count = $this -> Aladin_request_model -> orderbango_count_get($post_data['orderbango']);
                      // BPAオーダーだけを対象にする
                      if($count > 0){
                        $logmsg = "BPA->PCC : 処理が開始します。";
                        log_message('info', $logmsg);
                        $this -> call_pcc($post_data['transactionTYPE'], $up_denwabango, $post_data['orderbango']);
                        $logmsg = "BPA->PCC : 処理が完了しました。";
                        log_message('info', $logmsg);
                      }

                      // EXITログ
                      $logmsg = $api_name . ',' . $if_direction . "処理が完了しました。" . "[" . "orderbango:" . $post_data['orderbango'] . ", transactionTYPE:" . $post_data['transactionTYPE'] . ", denwabango:" . $post_data['denwabango'] . ", status:" . $up_status . "]" . " [updateCount:" . $update_count . "]" . " [HttpResponseCode:" . $http_response_code . "]";
                      log_message('info', $logmsg);

                      $mvno_body  = array();
                      $input_list = $this -> input_list_model -> select_list(array('orderbango' => $post_data['orderbango']))[0];
                      $input_data = $this -> input_data_model -> select_list(array('UID' => $input_list -> INPUT_DATA_UID))[0];
                      if($input_data -> INPUT_KBN == "2"){
                        $mvno_body['orderbango']      = $input_data -> orderbango;
                        $mvno_body['transactionTYPE'] = $post_data['transactionTYPE'];
                        $mvno_body['denwabango']      = $post_data['denwabango'];
                        $mvno_body['seizobango']      = $post_data['seizobango'];
                        $api_logmsg                   = "BPA->MVNO : 処理が開始します。";
                        log_message('info', $api_logmsg);
                        call_mvno_api($mvno_body, $input_data -> TENANT_ID, "sim/writing");
                        $api_logmsg = "BPA->MVNO : 処理が完了しました。";
                        log_message('info', $api_logmsg);
                      }

                    }else{
                        // 更新失敗
                        $http_response_code = HTTP_UNPROCESSABLE_ENTITY;
                        // エラーステータス更新
                        $update_count = $this->Aladin_request_model->update_input_list_error(API_STATUS_ERROR, $up_orderbango);
                        $logmsg = $api_name . ',' .$if_direction . "エラーが発生しました。" . "[" . "orderbango:" . $post_data['orderbango']. ", transactionTYPE:" . $post_data['transactionTYPE'] .", denwabango:" . $post_data['denwabango']. ", status:" . $up_status . "]" . " [updateCount:" . $update_count . "]" . " [HttpResponseCode:" . $http_response_code . "]";
                        log_message('info', $logmsg);
                    }
                    
                    break;
            }
        }else{
            // 不正リクエスト
            $http_response_code = HTTP_UNPROCESSABLE_ENTITY;
            // EXITログ
            $logmsg = $api_name . ',' .$if_direction . "不正リクエスト。" . "[" . "orderbango:" . $post_data['orderbango']. ", transactionTYPE:" . $post_data['transactionTYPE'] .", denwabango:" . $post_data['denwabango']. "]";
            log_message('info', $logmsg);
        }
        
        // HTTP処理結果
        http_response_code( $http_response_code );
    }
    
    public function index_get()
    {
        // 不正アクセス
        http_response_code( HTTP_NOT_FOUND ); // HTTP 404 Not Found
    }
    
    /**
     * @name post_data_set
     * @desc postデータ取得
     * @param array
     * @return array
     */
    private function post_data_set(){
        // 初期化
        $arr_post_data[]='';
        
        // オーダー番号
        $arr_post_data['orderbango']  = htmlspecialchars(trim($this->post('orderbango')));
        
        // トランザクションType
        $arr_post_data['transactionTYPE']  = htmlspecialchars(trim($this->post('transactionTYPE')));
        
        // 改廃年月日
        $arr_post_data['kaihainengappi']  = htmlspecialchars(trim($this->post('kaihainengappi')));
        
        // 電話番号
        $arr_post_data['denwabango']  = htmlspecialchars(trim($this->post('denwabango')));
        
        // 製造番号
        $arr_post_data['seizobango']  = htmlspecialchars(trim($this->post('seizobango')));
        
        $logmsg = 'ALADIN->BPA Post Data: transactionTYPE[' . $arr_post_data['transactionTYPE'] . '],' . 'orderbango[' . $arr_post_data['orderbango'] . '],denwabango[' . $arr_post_data['denwabango'] . '],kaihainengappi['. $arr_post_data['kaihainengappi'] .'],seizobango[' . $arr_post_data['seizobango'] .']';
        log_message('info', $logmsg);
        
        return $arr_post_data;
    }
    /**
     * @name validate
     * @desc バリデーションチェック
     * @param transactionTYPE
     * @param orderbango
     * @param orderbango
     * @return $return_flg
     */
    private function validate(String $transactionTYPE, String $orderbango, String $denwabango){
        // バリデーションチェックFLG
        $return_flg = RESULT_OK; // OK
        
        // オーダー番号チェック
        if(empty($orderbango)){
            return RESULT_NG;
        }
        
        // 電話番号チェック
        if(empty($denwabango)){
            return RESULT_NG;
        }
        
        // トランザクションTypeチェック
        switch ($transactionTYPE){
            case SIM_NEW :
            case SIM_HB_NEW :
            case SIM_STOP_RESTART :
            case SIM_MNP :
            case SIM_MNP_CANCEL :
            case SIM_END_CONTRACT :
            case SIM_SEARCH :
            case SIM_OPEN :
            case SIM_CHANGE :
                $return_flg = RESULT_OK; // OK
                break;
            default:
                $return_flg = RESULT_NG; // NG
        }
        
        return $return_flg;
    }
    
    /**
     * PCC Pro 連携処理
     * @param string $transactionTYPE
     * @param string $denwabango
     * @return
     */
    private function call_pcc($transactionTYPE, $denwabango, $orderbango)
    {
        $this->load->model('input_list_model');
        $pool_group = $this->input_list_model->select_list(array('orderbango' => $orderbango))[0]->POOL_GROUP; // Added by zz for real test.
        
        // 電話番号がブランクの場合、処理しない
        if (!isset($denwabango))
        {
            return;
        }
        
        // トランザクションタイプが「01、A2、49」以外の場合、処理しない
        if ($transactionTYPE != SIM_NEW and $transactionTYPE != SIM_HB_NEW)
        {
            return;
        }


        // ユーザーグループID取得
        $mvnoid = $this->Aladin_request_model->call_pcc_get_mvnoid($orderbango);
        
        // PCC Pro 連携処理
                $pro_data = array(
                        'login_name' => '81' . substr(trim($denwabango), 1),            // SIM ID
                        'password' => 'wakuwaku',                                       // パスワード
                        'access_group' => $mvnoid,                                     // TODO ユーザーグループID
                        'auth_start_date' => null,                                      // 認証開始日時
                        'auth_end_date' => null,                                        // 認証終了日時
                        'login_count' => 0,                                             // ログイン数
                        'max_login_count' => -1,                                        // 最大ログイン数
                        'invalid_flag' => FALSE,                                        // ログイン不可
                        'invalid_date' => NULL,                                         // ログイン不可日時
                        'password_error_count' => 0,                                    // 認証失敗回数
                        'personal_radius_attributes' => array(
	                        array(
		                        'attribute' => 'Framed-Pool',
		                        'value' => $pool_group // Added by zz for real test.
	                        )
                        ),
                        'restrict_attributes' => NULL,
                        'caller_id_count' => 0,
                        'password_cryption_type' => 0,
                        'prepayed_user' => 0,
                        'remain_time' => -1
                );
                
                log_message('info', 'BPA->PCC 送信データ:' . json_encode($pro_data, JSON_PRETTY_PRINT));

			$result = call_pcc_api($pro_data);
			log_message('info', json_encode($result, JSON_UNESCAPED_SLASHES));

			$result_2 = call_pcc_api_2($pro_data);
			log_message('info', json_encode($result_2, JSON_UNESCAPED_SLASHES));
    }
    
}
