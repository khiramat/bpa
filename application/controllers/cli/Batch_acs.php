<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * ACS呼出用データ作成コントローラ
 */
class Batch_acs extends CI_Controller{
	public function execute_input_call(){
		$this -> to_input_list_table();
		$this -> call_acs();
	}
	
	public function to_input_list_table(){
		if (!is_cli()) {
			die('Access denied');
		}
		log_message('info', '「入力データ」からの抽出処理が開始されました。');
		
		$this -> load -> model('input_data_model');
		$this -> load -> model('input_list_model');
		$this -> load -> helper('utility');
		
		$input_data_rows = $this -> input_data_model -> get_unprocessed_rows();// 未処理の件を全て取得
		
		// 料金プラン <=> 契約種別
		$contracttype_ryokinplan = array(
			'A1089' => '1',
			'A1046' => '2',
			'A1047' => '2',
			'A1048' => '2',
			'A1049' => '2',
			'A1050' => '2',
			'AC001' => '3',
			'AJ010' => '4',
			'AJ034' => '5',
			'AJ055' => '6'
		);
		
		$i = 0;// 挿入件数
		foreach ($input_data_rows as $input_data_row) {
			// INPUT_DATAテーブルからデータ抽出
			$this -> input_data_model -> change_input_status(INPUT_STATUS_DOING, $input_data_row -> UID);// 未入力　→　入力中
			// 入力データリストの登録処理
			log_message('info', '「入力データリスト」への挿入が開始されました。');
			
			$data                    = array();
			$data['INPUT_DATA_UID']  = $input_data_row -> UID;            // シーケンスID
			$data['ryokinplan']      = $input_data_row -> ryokinplan;     // 料金プラン
			$data['cardkeijo']       = $input_data_row -> cardkeijo;      // カード形状
			$data['transactionTYPE'] = $input_data_row -> transactionTYPE;// トランザクションType
			
			// 契約種別の取得
			if (isset($contracttype_ryokinplan[$data['ryokinplan']])) {
				$data['contracttype'] = $contracttype_ryokinplan[$data['ryokinplan']];
			} else {
				$data['contracttype'] = '';
			}
			
			$this -> db -> trans_begin();// トランザクションスタート
			for ($count = 0; $count < $input_data_row -> LINE_CNT; $count++)// 枚数まで挿入
			{
				// (01, A2) or 02　分岐処理
				if ($input_data_row -> transactionTYPE != "01" and $input_data_row -> transactionTYPE != "A2")
					$data['denwabango'] = $input_data_row -> denwabango;// MSISDN(電話番号)
				if ($input_data_row -> transactionTYPE == "02")
					$data['sousaservice'] = $input_data_row -> sousaservice;// 割引オプション
				
				$data['orderbango'] = create_orderbango(); // オーダー番号 : ランダム15桁数を生成
				// 2do begin : その他のパラメーター処理
				// $data['something'] = ...;
				// 2do end : その他のパラメーター処理
				$this -> input_list_model -> insert($data);// 入力データリストの登録処理を行う
				$i++;
			}
			if ($this -> db -> trans_status() === false) {// Transaction 以上終了
				$this -> db -> trans_rollback();
				$this -> input_data_model -> change_input_status(INPUT_STATUS_UNDO, $input_data_row -> UID);// 未処理
			} else {// Transaction 正常終了
				$this -> db -> trans_commit();
				$this -> input_data_model -> change_input_status(INPUT_STATUS_DONE, $input_data_row -> UID);// 入力完了
			}
			
		}// foreach ($input_data_rows as $input_data_row) end.
		
		
		log_message('info', sprintf('「入力リストテーブル」への挿入が終了しました。(新規抽出件数: %d 件)', $i));
	}
	// to_LIST_table() end
	
	/**
	 * call acs処理
	 * 入力リストテーブルからデータを抽出して、ACSへのAPIを呼び出します。
	 *
	 * @param int $process_cnt
	 *        一回で処理件数
	 */
	public function call_acs(int $process_cnt = 10000) {
		if (!is_cli()) {
			die('Access denied');
		}
		log_message('info', 'コールACS処理が開始されました。');
		
		$this -> load -> model('input_data_model');
		$this -> load -> model('input_list_model');
		$this -> load -> helper('property');
		$this -> load -> helper('utility');
		
		// 処理中の入力ステータスデータ
		$current_input_data = $this -> input_data_model -> get_next_input_data();
		if (empty($current_input_data)) {
			log_message('info', '未処理の INPUT_DATA はありません。コールACS処理を終了します。');
			return;
		}
		
		$uid_list_done = array();      // 処理完了のシーケンスID
		
		while (true) {
			try {
				if (!in_array($current_input_data -> UID, $uid_list_done)) {
					array_push($uid_list_done, $current_input_data -> UID);
				}

				// ====================== 中断かどうかのチェック START ====================== //
				if ($this -> input_data_model -> is_stopped($current_input_data -> UID)) {
					log_message('info', sprintf('画面側で中断ボタンが押されたので、次の処理は中断します。(シーケンスID: %d)', $current_input_data -> UID));
					
					// 入力データリストに残したデータ: 実行予約->中断
					$upd_cnt = $this -> input_list_model -> update(
						array(
							'CALL_STATUS' => CALL_STATUS_STOP               // コールステータス: 中断
						), array(
							'INPUT_DATA_UID' => $current_input_data -> UID,
							'CALL_STATUS'    => CALL_STATUS_RESERVE,           // コールステータス: 実行予約
							'DELETE_FLAG'    => DELETE_FLG_NOT_DELETE          // 削除フラグ: 未削除
						));
					log_message('info', sprintf('中断処理が完了しました。(シーケンスID: %d, 中断件数: %d)', $current_input_data -> UID, $upd_cnt));
					
					// 次の入力ステータスデータの取得処理
					$current_input_data = $this -> input_data_model -> get_next_input_data($uid_list_done);
					if (empty($current_input_data)) {
						// 処理終了
						break;
					}
					// 次のループへ
					continue;
				}
				// ====================== 中断かどうかのチェック END ====================== //

				// ====================== 実行予約のデータの取得処理 START ====================== //
				log_message('info', '実行予約のデータの取得処理を開始しました。');
				$process_datas = $this -> input_list_model -> select_process_data($current_input_data -> UID, $process_cnt);

				// ====================== 実行予約のデータの取得処理 END ====================== //
				
				// ====================== 全て実行完了のチェック START ====================== //
				if (empty($process_datas)) {
					log_message('info', "実行予約中の INPUT_LIST データはありません。". $current_input_data -> UID. "終了判定を行います。");
					// 入力リストに、コールステータスが[実行完了]以外のカウント
					$condition = array(
						'INPUT_DATA_UID' => $current_input_data -> UID,
						'CALL_STATUS <>' => CALL_STATUS_DONE,          // コールステータス: 実行完了
						'DELETE_FLAG'    => DELETE_FLG_NOT_DELETE      // 削除フラグ: 未削除
					);
					// 全部実行完了の場合
					if ($this -> input_list_model -> count($condition) == 0) {
						// 入力データのイベントステータスを変更する。実行中->実行完了
						$this -> input_data_model -> update(
							array(
								'CALL_STATUS' => CALL_STATUS_DONE
							),
							array(
								'UID' => $current_input_data -> UID
							));
						log_message('info', $current_input_data -> UID. ' のコールACS処理が完了しました。');
					}
					
					// 次の入力ステータスデータの取得処理
					$current_input_data = $this -> input_data_model -> get_next_input_data($uid_list_done);
					if (empty($current_input_data)) {
						log_message('info', '未処理の INPUT_DATA はありません。コールACS処理を終了します。');
						// 処理終了
						break;
					}
					// 次のループへ
					continue;
				}
				// ====================== 全て実行完了のチェック END ====================== //
				
				// ====================== ACS側への送信処理 START  ====================== //
				log_message('info', sprintf('実行予約のデータの取得処理を終了しました。(取得件数: %d 件)', count($process_datas)));
				log_message('info', 'ACS側への送信処理を開始しました。');
				foreach ($process_datas as $process_data) {
					$orderbango = create_orderbango();
					log_message('info', sprintf('[シーケンスID: %s] 処理開始。', $process_data -> UID));
					
					// 動作ステータスの変更: 未処理->実行中
					$this -> input_list_model -> update(
						array(
							'CALL_STATUS' => CALL_STATUS_DOING
						),
						array(
							'UID' => $process_data -> UID
						));
					
					// 作業用トランザクションタイプの取得
					$is_mnp                = empty($process_data -> MNPyoyakubango) ? false : true;
					$is_cardsaihakoFLG     = $process_data -> cardsaihakoFLG == CARD_SAIHAKO;
					$real_transaction_type = $process_data -> transactionTYPE . '-' . $process_data -> transactionKBN;
					// 送信用bodyの作成
					$body                    = array();
					$body['transactionTYPE'] = $process_data -> transactionTYPE; // トランザクションタイプ
					if (isset(get_acs_property('open')[$real_transaction_type])) {
						$propertyTypes = get_acs_property('open')[$real_transaction_type];
					} else {
						$propertyTypes = get_acs_property('modify')[$real_transaction_type];
					}
					foreach (array_keys($propertyTypes) as $p) {
						if (isset($process_data -> $p)) {
							$v        = $process_data -> $p;
							$body[$p] = $v == '' ? null : $v;
						} else {
							$body[$p] = null;
						}
					}
					$body['orderbango'] = $orderbango; // オーダー番号
					unset($body['LINE_CNT']);
					unset($body['TENANT_ID']);
					
					log_message('debug', '送信データ: ' . serialize($body));
					log_message('debug', 'テナントID: ' . $current_input_data -> TENANT_ID);
					
					// 送信処理を行う。
					$result = call_api($body, $current_input_data -> TENANT_ID);
					
					// 送信成功
					if (empty($result["body"])) {
						// 入力リストの更新処理
						$this -> input_list_model -> update(
							array(
								'orderbango'  => $orderbango,      // オーダー番号
								'CALL_STATUS' => CALL_STATUS_DONE, // 実行完了
								'ACS_RESULT'  => RESULT_OK         // 受付OK
							), array(
								'UID' => $process_data -> UID
							));
						log_message('info', sprintf('[シーケンスID: %s] 送信終了(新規オーダー番号: %s)。', $process_data -> UID, $orderbango));
						echo sprintf('[シーケンスID: %s] 処理成功。', $process_data -> UID) . br();
					} // 送信失敗
					else {
						$errors = json_decode($result["body"]);
						// 入力リストの更新処理
						$this -> input_list_model -> update(
							array(
								'CALL_STATUS' => CALL_STATUS_DONE,   // 実行完了
								'ACS_RESULT'  => $errors -> errcode  // NG: error code
							), array(
								'UID' => $process_data -> UID
							));
						log_message('error', sprintf('[シーケンスID: %s] 送信失敗(%s: %s)', $process_data -> UID, $errors -> errcode, $errors -> message));
						echo sprintf('[シーケンスID: %s] 処理失敗。', $process_data -> UID) . br();
					}
				} // foreach ($process_datas as $process_data) end
				$end_message = "ACS側への送信処理を終了しました。\n";
				log_message('info', $end_message);
				// ====================== ACS側への送信処理 END  ====================== //
			}
			catch (Exception $e) {
				log_message('error', 'コールACS処理が失敗しました。');
				log_message('error', $e -> getMessage());
				break;
			}
		}
		$call_message = "コールACS処理が終了しました。\n";
		log_message('info', $call_message);
		echo "コール終了";
	}
	
	// call_acs end
	
}


