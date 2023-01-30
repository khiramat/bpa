<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * オーダー編集用コントローラー
 */
class Order_edit extends CI_Controller {
    
    /**
     * データ初期化
     * @param string $input_data_uid 入力データシーケンスID
     * @param 
     */
    public function index(string $input_data_uid = NULL, string $uid = NULL)
    {
        $this->output->enable_profiler(FALSE);
        if (empty($input_data_uid))
        {
            $data['err_msg'] = 'データが見つかりませんでした。';
            $this->load->view('order/order_edit', $data);
            return;
        }
        
        $this->load->helper('property');
        $this->load->helper('utility');
        $this->load->model('input_data_model');
        $this->load->model('input_list_model');
        
        // 入力データの存在チェック
        $input_data = $this->input_data_model->select_one($input_data_uid);
        if ($input_data == null)
        {
            $data['err_msg'] = 'データが見つかりませんでした。';
            $this->load->view('order/order_edit', $data);
            return;
        }
        
        // 入力データリストIDがブランクの場合
        if (empty($uid))
        {
            $input_list = $this->input_list_model->select_list(array(
                    'INPUT_DATA_UID' => $input_data_uid,
                    'DELETE_FLAG' => DELETE_FLG_NOT_DELETE
            ));
            if (empty($input_list))
            {
                $data['err_msg'] = 'データが見つかりませんでした。';
                $this->load->view('order/order_edit', $data);
                return;
            }
            elseif (count($input_list) == 1)
            {
                redirect('order/order_edit/' . $input_data_uid . '/' . $input_list[0]->UID, 'location');
                exit();
            }
            else
            {
                redirect('order/order_input_list/' . $input_data_uid, 'location');
                exit();
            }
        }
        
        // 入力データリストの取得
        $input_list_info = $this->input_list_model->select_one($uid);
        if ($input_list_info && $input_data_uid == $input_list_info->INPUT_DATA_UID
                && $input_list_info->DELETE_FLAG != DELETE_FLG_DELETED)
        {
            // 実際作業用トランザクションタイプの取得
            $mnp_status = empty($input_list_info->MNPyoyakubango) ? FALSE : TRUE;
            $cardsaihako = $input_list_info->cardsaihakoFLG == CARD_SAIHAKO;
            $input_list_info->transactionTYPE = $input_list_info->transactionTYPE . '-' . $input_list_info->transactionKBN;
            
            // 返却データ
            $data['input_list_info'] = $input_list_info;
            $data['input_data_info'] = $input_data;
            
            if ($this->can_update($input_list_info))
            {
                $this->load->view('order/order_edit', $data);
            }
            else
            {
                $this->load->view('order/order_display', $data);
            }
        }
        else
        {
            $data['err_msg'] = 'データが見つかりませんでした。';
            $this->load->view('order/order_edit', $data);
        }
    }
    
    /**
     * データ更新処理
     */
    public function update()
    {
        if (!$this->input->is_ajax_request() || $this->input->method() != 'post')
        {
            die('Access denied');
        }
        
        $this->load->helper('property');
        $this->load->helper('utility');
        $this->load->model('input_data_model');
        $this->load->model('input_list_model');
        
        // トランザクションTYPE
        $transactionTYPE = htmlspecialchars(trim($this->input->post('transactionTYPE', TRUE)));
        // シーケンスID
        $uid = htmlspecialchars(trim($this->input->post('UID', TRUE)));
        
        // ******************* フォームバリデーション処理 ******************* //
        $form_validation_target = $transactionTYPE;
        // トランザクションTYPEが[検索] AND 検索項目が[MNP可否照会]以外の場合
        if ($transactionTYPE == 'M01-1' and $this->input->post('kensakukoumoku') != KENSAKU_KOUMOKU_MNP)
        {
            $form_validation_target = 'M01-23';
        }
        if ($this->form_validation->run($form_validation_target) == FALSE)
        {
            echo json_encode(array(
                    'success'=> false,
                    'message'=> validation_errors()
            ));
            exit();
        }
        
        // ******************* 更新前のチェック ******************* //
        $db_input_list_info = $this->input_list_model->select_one($uid);
        // 更新対象の存在チェック
        if (!$db_input_list_info or $db_input_list_info->DELETE_FLAG == DELETE_FLG_DELETED)
        {
            echo json_encode(array(
                    'success'=> false,
                    'message'=> '更新対象が見つかりませんでした。'
            ));
            exit();
        }
        // ステータスチェック: 「未実行/中断/実行失敗」のデータのみ更新できる
        if (!$this->can_update($db_input_list_info))
        {
            echo json_encode(array(
                    'success'=> false,
                    'message'=> '「未実行/中断」のデータのみ更新できる'
            ));
            exit();
        }
        
        // ******************* フォームデータの取得 ******************* //
        // フォームパラメータ
        if (isset(get_acs_property('open')[$transactionTYPE]))
        {
            $form_params = get_acs_property('open')[$transactionTYPE];
        }
        else 
        {
            $form_params = get_acs_property('modify')[$transactionTYPE];
        }
        foreach (array_keys($form_params) as $param)
        {
            $param_value = htmlspecialchars(trim($this->input->post($param, TRUE)));
            if (!isset($param_value) or $param_value === '')
            {
                $param_value = NULL;
            }
            $data[$param] = $param_value;
        }
        unset($data['LINE_CNT']);
        unset($data['TENANT_ID']);
        $transactionTYPE_arr = explode("-", $transactionTYPE);
        $data['transactionTYPE'] = $transactionTYPE_arr[0];                 // トランザクションTYPE
        $data['transactionKBN'] = $transactionTYPE_arr[1];                  // トランザクション区分
        $data['UID'] = $uid;                                                // シーケンスID
        $data['INPUT_DATA_UID'] = $db_input_list_info->INPUT_DATA_UID;      // 入力データシーケンスID
        $data['CALL_STATUS'] = $db_input_list_info->CALL_STATUS;            // コールステータス
        $data['CREATE_DATETIME'] = $db_input_list_info->CREATE_DATETIME;    // 登録日時
        
        // ******************* 個別パラメータの設定 ******************* //
        // カード再発行の場合
        if ($transactionTYPE == '02-2' || $transactionTYPE == 'M02-3')
        {
            $data['cardsaihakoFLG'] = '1';          // カード再発行フラグ
        }
        
        $input_data_uid = $this->input_list_model->convert_id($uid);
        // ******************* 更新処理を行う ******************* //
        $this->db->trans_start();
        $this->input_data_model->update(array("TENANT_ID" => $this->input->post("TENANT_ID")), array("UID" => $input_data_uid));
        
        $this->input_list_model->delete(array(
                'UID' => $uid
        ), FALSE);
        $this->input_list_model->insert($data);
        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
        }
        else
        {
            $this->db->trans_commit();
        }
        
        echo json_encode(array(
                'success'=> TRUE,
        ));
    }
    
    /**
     * MNP生年月日チェック
     * @param string $MNPseinengappi
     * @param string $MNPzokusei
     */
    function seinengappi_check($MNPseinengappi, $MNPzokusei)
    {
        $MNPzokusei = set_value($MNPzokusei);
        // [法人]の場合、MNP生年月日は[必須]ではない
        if (empty($MNPzokusei) || $MNPzokusei == MNP_ZOKUSEI_CORP)
        {
            return TRUE;
        }
        return empty($MNPseinengappi) ? FALSE : TRUE;
    }
    
    /**
     * アップデータ可否のチェック
     * @param stdClass $input_list_info
     * @param stdClass $input_data
     * @return bool
     */
    private function can_update($input_list_info) : bool 
    {
        // ステータスが「未実行/中断」のデータは、編集可能とする
        if ($input_list_info->CALL_STATUS == CALL_STATUS_UNDO
            or $input_list_info->CALL_STATUS == CALL_STATUS_STOP)
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }
    
}