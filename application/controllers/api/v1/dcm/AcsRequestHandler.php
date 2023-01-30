<?php
defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

/**
 * acs -> mvno api用コントローラー
 */
class AcsRequestHandler extends \Restserver\Libraries\REST_Controller
{

  /**
   * @name AcsRequestHandler
   * @desc ACS レスポンス受付 api
   * @param array
   * @return 正常:200
   */
  public function index_post()
  {
    // model load
    $this -> load -> model('input_data_model');
    $this -> load -> model('input_list_model');
    $this -> load -> model('api/v1/dcm/Acs_request_model');
    $this -> load -> helper('utility');

    // 通信方向
    $if_direction = "ACS->BPA";

    // I/F名称
    $api_name = "bpa-api";

    // HTTP処理結果コード
    $http_response_code = HTTP_OK;

    // ENTRYログ
    $logmsg = $api_name . ',' . $if_direction . "処理を開始します。";
    log_message('info', $logmsg);

    // リクエストヘッダチェック
    // ip check todo
    //$headers = getallheaders();
    //var_dump($headers);

    // POSTデータ取得
    $post_data = $this -> post_data_set();

    // acs ->bpa return code
    if(isset($post_data['syorikekkaKbn']) && $post_data['syorikekkaKbn'] != '' && $post_data['syorikekkaKbn'] != '00000' && $post_data['syorikekkaKbn'] != '00004'){

      // error code update
      $update_count = $this -> Acs_request_model -> update_input_list_error_code($post_data['orderbango'], $post_data['syorikekkaKbn']);

      $logmsg = $api_name . ',' . $if_direction . "処理が完了しました。syorikekkaKbn:" . $post_data['syorikekkaKbn'];
      log_message('info', $logmsg);

      http_response_code(HTTP_OK);
      exit;
    }

    // バリデーションチェック
    $validate_flg = $this -> validate($post_data['transactionTYPE'], $post_data['orderbango']);

    // postデータ有効場合
    if($validate_flg == RESULT_OK){
      // トランザクションType判定
      switch($post_data['transactionTYPE']){
        case SIM_NEW :
        case SIM_HB_NEW :
          $up_status = API_STATUS_REQUEST_OK;
          break;
        case SIM_STOP_RESTART :
        case SIM_MNP :
        case SIM_MNP_CANCEL :
        case SIM_END_CONTRACT :
        case SIM_SEARCH :
        case SIM_OPEN :
          $up_status = API_STATUS_RESPONSE_OK;
          break;
        case SIM_CHANGE :
          //カード再発行
          if($this -> Acs_request_model -> cardsaihakoFLG_chk($post_data['orderbango']) > 0){
            $up_status = API_STATUS_REQUEST_OK;
          } else {
            $up_status = API_STATUS_RESPONSE_OK;
          }
          break;
      }

      // オーダー番号
      $up_orderbango = $post_data['orderbango'];

      // 電話番号
      if(!empty($post_data['denwabango'])){
        $up_denwabango = $post_data['denwabango'];
      } else {
        $up_denwabango = '';
      }

      // orderbango 存在チェック
      $count = $this -> Acs_request_model -> orderbango_chk($up_orderbango);
      if($count > 0){
        // 処理なし
      } else {
        $logmsg = $api_name . ',' . $if_direction . "オーダー番号なし、処理が完了しました。";
        log_message('info', $logmsg);

        http_response_code(HTTP_OK);
        exit;
      }

      // 更新処理
      $logmsg = "INPUTLISTテーブルの更新処理を開始します。";
      log_message('info', $logmsg);
      
	    $denwabango_chk_result = $this -> Acs_request_model -> denwabango_chk($up_orderbango);
	    
	    if((isset($denwabango_chk_result->denwabango) || $denwabango_chk_result->denwabango != '') && ($post_data['transactionTYPE'] == SIM_NEW || $post_data['transactionTYPE'] == SIM_HB_NEW)){
		    $logmsg = $api_name . ',' . $if_direction . "既に電話番号が登録されているので処理をスキップしました。" . "[" . "orderbango:" . $post_data['orderbango'] . ", transactionTYPE:" . $post_data['transactionTYPE'] . ", denwabango:" . $up_denwabango . ", status:" . $up_status . "]" .  " [HttpResponseCode:" . $http_response_code . "]";
		    log_message('info', $logmsg);

	    	/*
	    	$overlapping_count = $this -> Acs_request_model -> overlapping($denwabango_chk_result);
		    if($overlapping_count > 0){
			    $update_count = 1;
		    } else {
		    
		    }
*/
	    } else {
		    $update_count = $this -> Acs_request_model -> update_input_list($up_status, $up_orderbango, $up_denwabango);
		
		    $logmsg = "INPUTLISTテーブルの更新処理が完了しました。" . '$update_count[' . $update_count . ']';
		    log_message('info', $logmsg);
		    if ($update_count > 0) {
			    $http_response_code = HTTP_OK;
			
			
			    // 解約オーダーだけを対象にする
			    if ($post_data['transactionTYPE'] == SIM_END_CONTRACT) {
				    $logmsg = "BPA->PCC : 処理を開始します。";
				    log_message('info', $logmsg);
				    $this -> call_pcc($post_data['transactionTYPE'], $post_data['denwabango']);
				    $logmsg = "BPA->PCC : 処理が完了しました。";
				    log_message('info', $logmsg);
			    }
			
			
			    // EXITログ
			    $logmsg = $api_name . ',' . $if_direction . "処理が完了しました。" . "[" . "orderbango:" . $post_data['orderbango'] . ", transactionTYPE:" . $post_data['transactionTYPE'] . ", denwabango:" . $up_denwabango . ", status:" . $up_status . "]" . " [updateCount:" . $update_count . "]" . " [HttpResponseCode:" . $http_response_code . "]";
			    log_message('info', $logmsg);
			
			    $mvno_body      = array();
			    $input_list     = $this -> input_list_model -> select_max_uid($post_data['denwabango'])[0];
			    $input_data_uid = $input_list -> INPUT_DATA_UID;
			    $input_data     = $this -> input_data_model -> select_order($input_data_uid)[0];
			    $orderbango     = $input_data -> orderbango;
			
			    if ($input_data -> INPUT_KBN == "2") {
				    if ($post_data["transactionTYPE"] == SIM_HB_NEW) {
					    $mvno_body['syorikekkaKbn']   = $post_data['syorikekkaKbn'];
					    $mvno_body['orderbango']      = $orderbango;
					    $mvno_body['transactionTYPE'] = $post_data['transactionTYPE'];
					    $mvno_body['denwabango']      = $post_data['denwabango'];
				    } else if ($post_data['transactionTYPE'] == SIM_OPEN) {
					    $mvno_body['syorikekkaKbn']   = $post_data['syorikekkaKbn'];
					    $mvno_body['orderbango']      = $orderbango;
					    $mvno_body['transactionTYPE'] = $post_data['transactionTYPE'];
					    $mvno_body["kaihainengappi"]  = $post_data['kaihainengappi'];
					    $mvno_body['denwabango']      = $post_data['denwabango'];
					    $mvno_body["riyoukaisibi"]    = $post_data['riyoukaisibi'];
					    $mvno_body["PINlockkaijo"]    = $post_data['PINlockkaijo'];
				    } else if ($post_data['transactionTYPE'] == SIM_STOP_RESTART) {
					    $mvno_body['syorikekkaKbn'] = $post_data['syorikekkaKbn'];;
					    $mvno_body["orderbango"]      = $orderbango;
					    $mvno_body["transactionTYPE"] = $post_data['transactionTYPE'];
					    $mvno_body["kaihainengappi"]  = $post_data['kaihainengappi'];
					    $mvno_body["denwabango"]      = $post_data['denwabango'];
					    $mvno_body["riyoukaisibi"]    = $post_data['riyoukaisibi'];
				    } else if ($post_data['transactionTYPE'] == SIM_END_CONTRACT) {
					    $mvno_body['syorikekkaKbn'] = $post_data['syorikekkaKbn'];;
					    $mvno_body["orderbango"]      = $orderbango;
					    $mvno_body["transactionTYPE"] = $post_data['transactionTYPE'];
					    $mvno_body["kaihainengappi"]  = $post_data['kaihainengappi'];
					    $mvno_body["denwabango"]      = $post_data['denwabango'];
				    }
			    }
			    $api_logmsg = "BPA->MVNO : 処理を開始します。";
			    log_message('info', $api_logmsg);
			    call_mvno_api($mvno_body, $input_data -> TENANT_ID, "order/result");
			    $api_logmsg = "BPA->MVNO : 処理が完了しました。\n";
			    log_message('info', $api_logmsg);
			
			    // 1st. リリース用 あとで削除
			    /*
									$mvno_body  = array();
									if($post_data["transactionTYPE"] == SIM_HB_NEW){
										$mvno_body['orderbango']      = $orderbango;
										$mvno_body['transactionTYPE'] = $post_data['transactionTYPE'];
										$mvno_body["kaihainengappi"]  = $post_data['kaihainengappi'];
										$mvno_body['denwabango']      = $post_data['denwabango'];
										$mvno_body['seizobango']      = "DN0504516798220";
									}
									$api_logmsg = "BPA->MVNO : 登録要求処理を開始します。1st. リリース用 あとで削除";
									log_message('info', $api_logmsg);
									call_mvno_api($mvno_body, $input_data -> TENANT_ID, "sim/writing");
									$api_logmsg = "BPA->MVNO : 登録要求処理が完了しました。1st. リリース用 あとで削除\n";
									log_message('info', $api_logmsg);
					*/
			    // ここまで
			
		    } else {
			    // 更新失敗
			    $http_response_code = HTTP_UNPROCESSABLE_ENTITY;
			    // ステータス更新
			    $update_count = $this -> Acs_request_model -> update_input_list_error(API_STATUS_ERROR, $up_orderbango);
			    $logmsg       = $api_name . ',' . $if_direction . "エラーが発生しました。" . "[" . "orderbango:" . $up_orderbango . ", transactionTYPE:" . $post_data['transactionTYPE'] . ", denwabango:" . $up_denwabango . ", status:" . $up_status . "]" . " [updateCount:" . $update_count . "]" . " [HttpResponseCode:" . $http_response_code . "]\n";
			    log_message('info', $logmsg);
		    }
	    }
    } else {
      // 不正リクエスト
      $http_response_code = HTTP_UNPROCESSABLE_ENTITY;

      // EXITログ
      $logmsg = $api_name . ',' . $if_direction . "不正リクエスト。" . "[" . "orderbango:" . $post_data['orderbango'] . ", transactionTYPE:" . $post_data['transactionTYPE'] . "]\n";
      log_message('info', $logmsg);
    }
    // HTTP処理結果
    http_response_code($http_response_code);
  }

  public function index_get()
  {
    // アクセス不正
    http_response_code(HTTP_NOT_FOUND); // HTTP 404 Not Found
  }

  /**
   * @name post_data_set
   * @desc postデータ取得
   * @param array
   * @return array
   */
  private function post_data_set()
  {
    // 初期化
    $arr_post_data[] = '';

    log_message('debug', "ACS-BPA all post data: " . serialize($_POST));


    // 処理区分
    $arr_post_data['syorikekkaKbn'] = htmlspecialchars(trim($this -> post('syorikekkaKbn')));

    // トランザクションType
    $arr_post_data['transactionTYPE'] = htmlspecialchars(trim($this -> post('transactionTYPE')));

    // オーダー番号
    $arr_post_data['orderbango'] = htmlspecialchars(trim($this -> post('orderbango')));

    // 電話番号
    $arr_post_data['denwabango'] = htmlspecialchars(trim($this -> post('denwabango')));

    // カード再発行フラグ
    $arr_post_data['cardsaihakoFLG'] = htmlspecialchars(trim($this -> post('cardsaihakoFLG')));


    // 開廃年月日
    $arr_post_data['kaihainengappi'] = htmlspecialchars(trim($this -> post('kaihainengappi')));

    // PINロック解除コード(PUK)
    $arr_post_data['PINlockkaijo'] = htmlspecialchars(trim($this -> post('PINlockkaijo')));

    // 利用開始日
    $arr_post_data['riyoukaisibi'] = htmlspecialchars(trim($this -> post('riyoukaisibi')));


    $logmsg = 'ACS->BPA Post Data: syorikekkaKbn[' . $arr_post_data['syorikekkaKbn'] . ']' . 'transactionTYPE[' . $arr_post_data['transactionTYPE'] . '],' . 'orderbango[' . $arr_post_data['orderbango'] . '],denwabango[' . $arr_post_data['denwabango'] . '],cardsaihakoFLG[' . $arr_post_data['cardsaihakoFLG'] . ']';
    log_message('info', $logmsg);

    return $arr_post_data;
  }

  /**
   * @name validate
   * @desc   バリデーションチェック
   * @param transactionTYPE
   * @param orderbango
   * @return $return_flg
   */
  private function validate(String $transactionTYPE, String $orderbango)
  {
    // バリデーションチェックFLG
    $return_flg = RESULT_OK; // OK

    // オーダー番号チェック
    if(empty($orderbango)){
      return RESULT_NG; // NG
    }

    // トランザクションTypeチェック
    switch($transactionTYPE){
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
  private function call_pcc($transactionTYPE, $denwabango)
  {

    // 電話番号がブランクの場合、処理しない
    if(!isset($denwabango)){
      return;
    }

    // トランザクションタイプが「49」以外の場合、処理しない
    if($transactionTYPE != SIM_END_CONTRACT){
      return;
    }

    // PCC Pro 連携処理
    $pro_data = '81' . substr(trim($denwabango), 1);            // SIM ID

    log_message('info', '解約処理開始');
    log_message('info', 'BPA->PCC 送信データ:' . $pro_data);

		$result = call_pcc_delete_api($pro_data);
		log_message('info', json_encode($result, JSON_UNESCAPED_SLASHES));
		$result_2 = call_pcc_delete_api_2($pro_data);
		log_message('info', json_encode($result_2, JSON_UNESCAPED_SLASHES));

    log_message('info', '解約処理');

  }

}
