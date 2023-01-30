<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * API新規オーダー用コントローラー
 */
class New_order extends CI_Controller {

	/**
	 * API新規オーダー用
	 */
	public function index(int $active_tab = 1) {
		$this->load->helper('property');
		$this->load->model('tenant_model');
		$this->output->enable_profiler(TRUE);

		// 開通/変更の場合
		if ($active_tab != 3) {
			// テナント情報リストの取得処理
			$tenant_list         = $this->tenant_model->select_list(array(
				'DELETE_FLAG' => DELETE_FLG_NOT_DELETE      // 削除フラグ: 未削除
			));
			$data['tenant_list'] = $tenant_list;
		}

		// 返却データ
		$data['active_tab'] = $active_tab;
		$this->load->view('order/new_order', $data);
	}

	/**
	 * データ検証処理
	 */
	public function validate() {
		if (!$this->input->is_ajax_request() || $this->input->method() != 'post') {
			die('Access denied');
		}
		if (!$this->do_validation()) {
			echo json_encode(array(
				'success' => FALSE,
				'message' => validation_errors()
			));
			exit();
		}

		echo json_encode(array(
			'success' => TRUE
		));

	}
	// validate end

	/**
	 * SIM開通処理
	 */
	public function sim_open() {
		$this->insert(1);
	}
	// do_validation end

	/**
	 * SIM変更処理
	 */
	public function sim_modify() {
		$this->insert(2);
	}
	// SIM開通処理 end

	/**
	 * MNP生年月日チェック
	 * @param string $MNPseinengappi
	 * @param string $MNPzokusei
	 */
	function seinengappi_check($MNPseinengappi, $MNPzokusei) {
		$MNPzokusei = set_value($MNPzokusei);
		// [法人]の場合、MNP生年月日は[必須]ではない
		if (empty($MNPzokusei) || $MNPzokusei == '3') {
			return TRUE;
		}
		return empty($MNPseinengappi) ? FALSE : TRUE;
	}
	// SIM変更処理 END

	/**
	 * ファイルアップロード処理
	 */
	public function upload_file() {
		if (!empty($_FILES) && !empty($_FILES['file']['name'])) {
			// ファイルアップロード config
			$config['upload_path']   = FCPATH . 'upload/';
			$config['allowed_types'] = 'xls|xlsx';
			$config['max_size']      = 2048;
			$config['file_name']     = time() . '_' . $_FILES['file']['name'];
			$this->load->library('upload', $config);

			// アップロード処理を行う
			if ($this->upload->do_upload('file')) {
				$upload_data = $this->upload->data();
				try {
					// 登録処理を行う
					$insert_result = $this->insert_from_file($upload_data);
					// 登録失敗の場合
					if (!$insert_result['success']) {
						log_message('error', sprintf('%s(ファイル名: %s)', $insert_result['message'], $upload_data['file_name']));
					}
					echo json_encode($insert_result);
				}
				catch (Exception $e) {
					log_message('error', $e->getTraceAsString());
					echo json_encode(array(
						'success' => FALSE,
						'message' => $e->getMessage()
					));
				}
				finally {
					// ファイル削除
					unlink($upload_data['full_path']);
				}
			} else {
				echo json_encode(array(
					'success' => FALSE,
					'message' => $this->upload->display_errors('', '')
				));
			}
		} else {
			echo json_encode(array(
				'success' => FALSE,
				'message' => '未知のエラーが発生しました。'
			));
		}
	}
	// データ登録処理 end

	/**
	 * do_validation
	 * @return bool
	 */
	private function do_validation(): bool {
		// トランザクションTYPE
		$transactionTYPE = htmlspecialchars(trim($this->input->post('transactionTYPE', TRUE)));

		// ******************* フォームバリデーション処理 ******************* //
		$form_validation_target = $transactionTYPE;
		// トランザクションTYPEが[検索] AND 検索項目が[MNP可否照会]以外の場合
		if ($transactionTYPE == 'M01-1' and $this->input->post('kensakukoumoku') != KENSAKU_KOUMOKU_MNP) {
			$form_validation_target = 'M01-23';
		}
		return $this->form_validation->run($form_validation_target) ? TRUE : FALSE;
	}

	/**
	 * データ登録処理
	 */
	private function insert($present_tab = 1) {
		$this->load->model("sim_inventory_model");

		if (!$this->input->is_ajax_request() || $this->input->method() != 'post') {
			die('Access denied');
		}

		// ================= フォームバリデーション ==================== //
		if (!$this->do_validation()) {
			echo json_encode(array(
				'success' => FALSE,
				'message' => validation_errors()
			));
			exit();
		}

		$this->load->helper('utility');
		$this->load->helper('property');
		$this->load->model('input_data_model');
		$this->load->model('input_list_model');

		// トランザクションTYPE
		$transactionTYPE = $this->input->post('transactionTYPE');

		// ******************* フォームデータの取得 ******************* //
		// フォームパラメータ
		if (isset(get_acs_property('open')[$transactionTYPE])) {
			$form_params = get_acs_property('open')[$transactionTYPE];
		} else {
			$form_params = get_acs_property('modify')[$transactionTYPE];
		}

		// form data
		$transactionTYPE_arr     = explode("-", $transactionTYPE);
		$data['transactionTYPE'] = $transactionTYPE_arr[0]; // トランザクションTYPE
		$data['transactionKBN']  = $transactionTYPE_arr[1]; // トランザクション区分
		foreach (array_keys($form_params) as $param) {
			$param_value = $this->input->post($param);
			if (!isset($param_value) or $param_value === '') {
				$param_value = NULL;
			}
			$data[$param] = $param_value;
		}

		// ******************* 個別パラメータの設定 ******************* //
		// カード再発行の場合
		if ($transactionTYPE == '02-2' or $transactionTYPE == 'M02-3') {
			$data['cardsaihakoFLG'] = CARD_SAIHAKO;          // カード再発行フラグ
		}

		$line_cnt  = isset($data['LINE_CNT']) ? $data['LINE_CNT'] : 1;       // 枚数
		$tenant_id = $data['TENANT_ID'];                                    // テナントID
		unset($data['LINE_CNT']);
		unset($data['TENANT_ID']);

		// ******************* DB登録 ******************* //
		$this->db->trans_start();
		// 入力データ
		$this->input_data_model->insert(array(
			'TENANT_ID'       => $tenant_id,                                                      // テナントID
			'ryokinplan'      => isset($data['ryokinplan']) ? $data['ryokinplan'] : NULL,        // 料金プラン
			'cardkeijo'       => isset($data['cardkeijo']) ? $data['cardkeijo'] : NULL,           // カード形状
			'transactionTYPE' => $data['transactionTYPE'],                                  // トランザクションタイプ
			'LINE_CNT'        => $line_cnt,                                                        // 枚数
			'denwabango'      => isset($data['denwabango']) ? $data['denwabango'] : NULL,        // 電話番号
			'sousaservice'    => isset($data['sousaservice']) ? $data['sousaservice'] : NULL,  // 割引プラン
			'INPUT_STATUS'    => INPUT_STATUS_DONE,                                            // 入力ステータス: 入力完了
			'CALL_STATUS'     => CALL_STATUS_UNDO,                                              // コールステータス: 未処理
			'INPUT_KBN'       => INPUT_KUBUN_PAGE,                                                // 入力区分: 画面
		));

		// シーケンスID
		$data['INPUT_DATA_UID'] = $this->db->insert_id();

		if ($present_tab == 1) {  // SIM開通(単数、複数)
			// input_listテーブルにレコードを追加
			$returned_sim = $this->sim_inventory_model->select_list(array('DELETE_FLAG' => 0, 'USED_BY_OPEN' => 0), array('UPDATE_DATETIME' => 'asc')); // non deletion, reserved by open operation.
			for ($i = 0; $i < $line_cnt; $i++) // 枚数だけ繰り返す。
			{
				// 在庫テーブルから製造番号を取込んで、input_listへ製造番号を割当てる。
				$this->sim_inventory_model->update(array('USED_BY_OPEN' => 1), array('UID' => $returned_sim[$i]->UID)); // used_by_openフィールドを１に設定
				$data['SEIZOBANGO'] = $returned_sim[$i]->SEIZOBANGO; // 製造番号を割当てる。
				$this->input_list_model->insert($data);
				$message = count($data);
				log_message('info', $message);
			}
		} else { // SIM変更(は対象が一個なので、ループ処理は要らない)
			// input_listテーブルにレコードを追加
			$this->input_list_model->insert($data);
		}

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			echo json_encode(array(
				'success' => FALSE,
				//                     'message'=> '登録処理が失敗しました。'
				'message' => $data
			));
		} else {
			$this->db->trans_commit();
			echo json_encode(array(
				'success' => TRUE,
				'message' => json_encode($data)
			));
		}
	}
	// upload file end

	/**
	 * 入力データの登録処理
	 * @param array $upload_data
	 * @return array
	 */
	private function insert_from_file(array $upload_data): array
	{
		$this->load->model('input_data_model');
		$this->load->library('file_process/excel_reader');
		$this->load->library('file_process/excel_readerAdaptor');

		// ファイルの存在チェック
		if (!is_file($upload_data['full_path'])) {
			return array(
				'success' => FALSE,
				'message' => 'アップロードが失敗しました。'
			);
		}

		log_message('debug', sprintf('ファイル[%s]の読込が開始されました。', $upload_data['file_name']));

		// EXCELファイルの読み込み
		$excel_reader = new Excel_reader();
		$excel_reader->set_file($upload_data['full_path']);// ファイルの読込み
		$excel_adaptor = new Excel_readerAdaptor();
		$excel_adaptor->set_reader($excel_reader);

		$cnt = 0; // 処理件数
		$this->db->trans_begin();// トランザクションスタート
		foreach ($excel_adaptor->get_all_lines() as $line) {
			// ============= レイアウトチェック ============= //
			if (count($line) < 7) {
				log_message('debug', sprintf('レイアウトが不正の為に、ファイル[%s]の読込が失敗しました。', $upload_data['file_name']));
				return array(
					'success' => FALSE,
					'message' => 'ファイルレイアウトが不正です。'
				);
			}

			// ============= フォームバリデーション ============= //
			// data
			$data                    = array();
			$data['TENANT_ID']       = $line[0];                      // テナントID
			$data['ryokinplan']      = $line[1];                     // 料金プラン
			$data['cardkeijo']       = $line[2];                      // カード形状
			$data['transactionTYPE'] = $line[3];                // トランザクションType
			$data['LINE_CNT']        = $line[4];                       // 枚数
			$data['denwabango']      = $line[5];                     // 電話番号
			$data['sousaservice']    = $line[6];                   // 割引オプション

			$this->form_validation->reset_validation()->set_data($data);
			if ($this->form_validation->run('acs/input/upload') == FALSE) {
				log_message('debug', sprintf('フォームバリデーションエラー。(%s)', serialize($data)));
				// 次のline
				continue;
			}
			// 電話番号: トランザクションタイプ01, A2以外は必須
			if ($data['transactionTYPE'] != SIM_NEW and $data['transactionTYPE'] != SIM_HB_NEW
			                                            and empty($data['denwabango'])) {
				log_message('debug', sprintf('トランザクションタイプ[01,A2]以外の場合、電話番号が必要です。(%s)', serialize($data)));
				continue;
			}

			// ============= テナントIDの存在チェック ============= //
			// TODO

			// ================ その他データ設定 ================= //
			// 枚数: トランザクションタイプ 01, A2 以外は1に固定
			if ($data['transactionTYPE'] == SIM_NEW or $data['transactionTYPE'] == SIM_HB_NEW) {
				$data['LINE_CNT'] = empty($data['LINE_CNT']) ? 1 : $data['LINE_CNT'];
			} else {
				$data['LINE_CNT'] = 1;
			}
			// 割引オプション: トランザクションタイプ02のみ
			if ($data['transactionTYPE'] != SIM_CHANGE) {
				$data['sousaservice'] = NULL;
			}
			// 入力区分: ファイル
			$data['INPUT_KBN'] = INPUT_KUBUN_FILE;
			// 入力ファイル名
			$data['INPUT_FILE_NAME'] = $upload_data['file_name'];

			// 登録処理を行う
			if ($this->input_data_model->insert($data) == 1) {
				$cnt++;
			}
		} // foreach end

		// Transaction 異常終了
		if ($this->db->trans_status() === FALSE) {
			$cnt = 0;
			$this->db->trans_rollback();
		} // Transaction 正常終了
		else {
			$this->db->trans_commit();
		}

		// 新規登録件数が1件以上の場合
		if ($cnt > 0) {
			log_message('debug', sprintf('ファイル[%s]の読込が終了しました。(新規登録件数: %d)', $upload_data['file_name'], $cnt));
			return array(
				'success'    => TRUE,
				'insert_cnt' => $cnt
			);
		} else {
			log_message('debug', sprintf('ファイルの中身が不正の為に、ファイル[%s]の読込が失敗しました。', $upload_data['file_name']));
			return array(
				'success' => FALSE,
				'message' => 'ファイルの中身が正しくありません。'
			);
		}
	}
	// insert_from_file end

}