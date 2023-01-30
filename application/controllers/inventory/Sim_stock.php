<?php
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Csv;

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * sim在庫管理用コントローラー
 */
class Sim_stock extends CI_Controller {
    
    /**
     * SIM在庫一覧画面
     */
    public function index($cur_page = 1)
    {
        $this->load->library('pagination');
        $this->load->helper('property');
        $this->load->helper('utility');
        $this->load->model('sim_inventory_model');
        
        // 1ページで表示件数
        if ($this->session->has_userdata('inventory_sim_stock_per_page'))
        {
            $per_page = $this->session->userdata('inventory_sim_stock_per_page');
        }
        else
        {
            $per_page = array_keys(get_select_property('page_size'))[0];
        }
        
        // 検索条件の作成
        if ($this->session->has_userdata('inventory_sim_stock_search_condition'))
        {
            $search_condition = $this->session->userdata('inventory_sim_stock_search_condition');
        }
        $search_condition['DELETE_FLAG'] = DELETE_FLG_NOT_DELETE;   // 未削除
        
        // あいまい検索条件
        if ($this->session->has_userdata('inventory_sim_stock_search_target'))
        {
            $search_target = $this->session->userdata('inventory_sim_stock_search_target');
        }
        else
        {
            $search_target = NULL;
        }
        
        // 総件数の取得
        $total_rows = $this->sim_inventory_model->count($search_condition, $search_target);
        
        // 返却結果
        $data['cur_page'] = $cur_page;
        $data['per_page'] = $per_page;
        $data['total_rows'] = $total_rows;
        $data['search_target'] = $search_target;
        
        // 1件以上の場合
        if ($total_rows > 0)
        {
            // current page
            $max_page = ceil($total_rows / $per_page);
            if ($cur_page > $max_page)
            {
                $cur_page = $max_page;
            }
            // pagination初期化
            $config['base_url'] = site_url('inventory/sim_stock');
            $config['total_rows'] = $total_rows;
            $config['per_page'] = $per_page;
            $this->pagination->initialize($config);
            
            // ページデータの取得
            $sim_stock = $this->sim_inventory_model->select_page_datas($per_page,
                ($cur_page - 1) * $per_page, $search_condition, $search_target);
            
            // 統計情報の取得
            $statistics = $this->sim_inventory_model->select_statistics($search_condition, $search_target);
            
            // 返却結果
            $data['page_links'] = $this->pagination->create_links();
            $data['sim_stock'] = $sim_stock;
            $data['statistics'] = $statistics;
        }
        
        // 検索条件の再設定
        if (!empty($search_condition))
        {
            // SIMタイプ
            $data['SIM_TYPE'] = isset($search_condition['SIM_TYPE']) ? $search_condition['SIM_TYPE'] : '';
            // 発送状態
            $data['SHIPMENT_FLAG'] = isset($search_condition['SHIPMENT_FLAG']) ? $search_condition['SHIPMENT_FLAG'] : '';
            // 入庫日(始)
            if (isset($search_condition['ARRIVAL_DATETIME >=']))
            {
                $data['ARRIVAL_DATETIME_FROM'] = date('Ymd', strtotime($search_condition['ARRIVAL_DATETIME >=']));
            }
            else
            {
                $data['ARRIVAL_DATETIME_FROM'] = '';
            }
            // 入庫日(終)
            if (isset($search_condition['ARRIVAL_DATETIME <=']))
            {
                $data['ARRIVAL_DATETIME_TO'] = date('Ymd', strtotime($search_condition['ARRIVAL_DATETIME <=']));
            }
            else
            {
                $data['ARRIVAL_DATETIME_TO'] = '';
            }
            // 出庫日(始)
            if (isset($search_condition['SHIPMENT_DATETIME >=']))
            {
                $data['SHIPMENT_DATETIME_FROM'] = date('Ymd', strtotime($search_condition['SHIPMENT_DATETIME >=']));
            }
            else
            {
                $data['SHIPMENT_DATETIME_FROM'] = '';
            }
            // 出庫日(終)
            if (isset($search_condition['SHIPMENT_DATETIME <=']))
            {
                $data['SHIPMENT_DATETIME_TO'] = date('Ymd', strtotime($search_condition['SHIPMENT_DATETIME <=']));
            }
            else
            {
                $data['SHIPMENT_DATETIME_TO'] = '';
            }
        }
        else
        {
            // SIMタイプ
            $data['SIM_TYPE'] = '';
            // 発送状態
            $data['SHIPMENT_FLAG'] = '';
            // 入庫日（始）
            $data['ARRIVAL_DATETIME_FROM'] = '';
            // 入庫日（終）
            $data['ARRIVAL_DATETIME_TO'] = '';
            // 出庫日（始）
            $data['SHIPMENT_DATETIME_FROM'] = '';
            // 出庫日（終）
            $data['SHIPMENT_DATETIME_TO'] = '';
        }
        
        $this->load->view('inventory/sim_stock', $data);
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
        $this->session->set_userdata('inventory_sim_stock_per_page', $per_page);
        
        // SIM在庫表画面の表示
        redirect('inventory/sim_stock', 'location');
    }
    
    
    /**
     * 検索処理
     */
    public function do_search()
    {
        $condition = NULL;
        // SIMタイプ
        $sim_type = htmlspecialchars(trim($this->input->post('SIM_TYPE', TRUE)));
        if ($sim_type !== '')
        {
            $condition['SIM_TYPE'] = $sim_type;
        }
        // 発送状態
        $shipment_flag = htmlspecialchars(trim($this->input->post('SHIPMENT_FLAG', TRUE)));
        if ($shipment_flag !== '')
        {
            $condition['SHIPMENT_FLAG'] = $shipment_flag;
        }
        // 入荷日 (始)
        $arrival_datetime_from = htmlspecialchars(trim($this->input->post('ARRIVAL_DATETIME_FROM', TRUE)));
        if ($arrival_datetime_from !== '' and $this->form_validation->valid_date($arrival_datetime_from, 'Y/m/d'))
        {
            $condition['ARRIVAL_DATETIME >='] = date('Y-m-d H:i:s', strtotime($arrival_datetime_from));
        }
        // 入荷日 (終)
        $arrival_datetime_to = htmlspecialchars(trim($this->input->post('ARRIVAL_DATETIME_TO', TRUE)));
        if ($arrival_datetime_to !== '' and $this->form_validation->valid_date($arrival_datetime_to, 'Y/m/d'))
        {
            $condition['ARRIVAL_DATETIME <='] = date('Y-m-d H:i:s', strtotime($arrival_datetime_to . '235959'));
        }
        // 出荷日 (始)
        $shipment_datetime_from = htmlspecialchars(trim($this->input->post('SHIPMENT_DATETIME_FROM', TRUE)));
        if ($shipment_datetime_from !== '' and $this->form_validation->valid_date($shipment_datetime_from, 'Y/m/d'))
        {
            $condition['SHIPMENT_DATETIME >='] = date('Y-m-d H:i:s', strtotime($shipment_datetime_from));
        }
        // 出荷日 (終)
        $shipment_datetime_to = htmlspecialchars(trim($this->input->post('SHIPMENT_DATETIME_TO', TRUE)));
        if ($shipment_datetime_to !== '' and $this->form_validation->valid_date($shipment_datetime_to, 'Y/m/d'))
        {
            $condition['SHIPMENT_DATETIME <='] = date('Y-m-d H:i:s', strtotime($shipment_datetime_to . '235959'));
        }
        
        
        // set session
        if ($condition != NULL)
        {
            $this->session->set_userdata('inventory_sim_stock_search_condition', $condition);
        }
        else
        {
            $this->session->unset_userdata('inventory_sim_stock_search_condition');
        }
        
        // 検索項目
        $search_target = htmlspecialchars(trim($this->input->post('search_target', TRUE)));
        if (!empty($search_target))
        {
            $this->session->set_userdata('inventory_sim_stock_search_target', $search_target);
        }
        else
        {
            $this->session->unset_userdata('inventory_sim_stock_search_target');
        }
        
        // SIM在庫表画面の表示
        redirect('inventory/sim_stock', 'location');
    }
    
    /**
     * ダウンロード処理
     * @param int $type [1: 全てダウンロード、 2: 選択された部分のみ]
     */
    public function download(int $type)
    {
        // ajax check
        if (!$this->input->is_ajax_request() || $this->input->method() != 'post')
        {
            die('Access denied');
        }
        $this->load->model('sim_inventory_model');
        $this->load->helper('property');
        $this->load->helper('utility');
        
        // 検索条件の作成
        if ($this->session->has_userdata('inventory_sim_stock_search_condition'))
        {
            $search_condition = $this->session->userdata('inventory_sim_stock_search_condition');
        }
        $search_condition['DELETE_FLAG'] = DELETE_FLG_NOT_DELETE;   // 未削除
        
        // あいまい検索条件
        if ($this->session->has_userdata('inventory_sim_stock_search_target'))
        {
            $search_target = $this->session->userdata('inventory_sim_stock_search_target');
        }
        else
        {
            $search_target = NULL;
        }
        
        // 全てダウンロードする場合
        if ($type == 1) 
        {
            $uid_list = NULL;
        }
        else 
        {
            $uid_list = $this->input->post('uid_list', TRUE);
            // 単体チェック
            if (empty($uid_list))
            {
                echo json_encode(array(
                        'success'=> false,
                        'message'=> 'ダウンロード対象が見つかりませんでした。'
                ));
                exit();
            }
        }
        
        // ページデータの取得
        $sim_stock = $this->sim_inventory_model->select_download_datas($search_condition, $search_target, $uid_list);
        if (empty($sim_stock)) 
        {
            echo json_encode(array(
                    'success'=> false,
                    'message'=> 'ダウンロード対象が見つかりませんでした。'
            ));
            exit();
        }
        
        // csvファイルの作成
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->createSheet(0);
        
        // select list
        $sim_type_list = get_select_property('sim_type');
        $shipment_flag_list = get_select_property('shipment_flag');
        $ryokinplan_list = get_select_property('ryokinplan');
        $tukehenhai_list = array(
                '0'=> '廃止',
                '1'=> '新付',
                '2'=> '変更',
        );
        
        // csv header
        $col_index = 1;
        $row_index = 1;
        $sheet->setCellValueByColumnAndRow($col_index++, $row_index, 'NO');
        $sheet->setCellValueByColumnAndRow($col_index++, $row_index, 'メーカー');
        $sheet->setCellValueByColumnAndRow($col_index++, $row_index, '製造番号');
        $sheet->setCellValueByColumnAndRow($col_index++, $row_index, '代表回線');
        $sheet->setCellValueByColumnAndRow($col_index++, $row_index, '電話番号');
        $sheet->setCellValueByColumnAndRow($col_index++, $row_index, '暗証番号');
        $sheet->setCellValueByColumnAndRow($col_index++, $row_index, 'SIMタイプ');
        $sheet->setCellValueByColumnAndRow($col_index++, $row_index, '入荷日');
        $sheet->setCellValueByColumnAndRow($col_index++, $row_index, '出荷状態');
        $sheet->setCellValueByColumnAndRow($col_index++, $row_index, '出荷日');
        $sheet->setCellValueByColumnAndRow($col_index++, $row_index, '納品希望日');
        $sheet->setCellValueByColumnAndRow($col_index++, $row_index, '出荷先');
        $sheet->setCellValueByColumnAndRow($col_index++, $row_index, '出荷先2');
        $sheet->setCellValueByColumnAndRow($col_index++, $row_index, 'MVNO番号');
        $sheet->setCellValueByColumnAndRow($col_index++, $row_index, 'Pool番号');
        $sheet->setCellValueByColumnAndRow($col_index++, $row_index, '半黒化日');
        $sheet->setCellValueByColumnAndRow($col_index++, $row_index, '開通日');
        $sheet->setCellValueByColumnAndRow($col_index++, $row_index, '再発行日');
        $sheet->setCellValueByColumnAndRow($col_index++, $row_index, '解約日');
        $sheet->setCellValueByColumnAndRow($col_index++, $row_index, '貸出日');
        $sheet->setCellValueByColumnAndRow($col_index++, $row_index, 'RS返却日');
        $sheet->setCellValueByColumnAndRow($col_index++, $row_index, 'ドコモ返却依頼日');
        $sheet->setCellValueByColumnAndRow($col_index++, $row_index, '料金プラン');
        $sheet->setCellValueByColumnAndRow($col_index++, $row_index, '国際電話WORLD　CALL');
        $sheet->setCellValueByColumnAndRow($col_index++, $row_index, '国際ローミングWORLD　WING');
        $sheet->setCellValueByColumnAndRow($col_index++, $row_index, '課金情報機能');
        $sheet->setCellValueByColumnAndRow($col_index++, $row_index, 'キャッチホン');
        $sheet->setCellValueByColumnAndRow($col_index++, $row_index, '転送でんわ');
        $sheet->setCellValueByColumnAndRow($col_index++, $row_index, '国際着信転送');
        $sheet->setCellValueByColumnAndRow($col_index++, $row_index, '留守番電話');
        $sheet->setCellValueByColumnAndRow($col_index++, $row_index, '仕入価格');
        $sheet->setCellValueByColumnAndRow($col_index++, $row_index, '販売価格');
        $sheet->setCellValueByColumnAndRow($col_index++, $row_index, 'マージン');
        $sheet->setCellValueByColumnAndRow($col_index++, $row_index, '開通手数料');
        $sheet->setCellValueByColumnAndRow($col_index++, $row_index, '備考');
        
        // csv body
        foreach ($sim_stock as $sim_info)
        {
            $col_index = 1;
            $row_index++;
            
            // 操作サービス
            $sousaservice_arr = convert_sousaservice($sim_info->sousaservice);
            
            // NO
            $sheet->setCellValueByColumnAndRow($col_index++, $row_index, $sim_info->UID);
            // メーカー
            $sheet->setCellValueByColumnAndRow($col_index++, $row_index, substr($sim_info->SEIZOBANGO, 0, 2));
            // 製造番号
            $sheet->setCellValueByColumnAndRow($col_index++, $row_index, $sim_info->SEIZOBANGO);
            // 代表回線
            $sheet->setCellValueByColumnAndRow($col_index++, $row_index, '-');
            // 電話番号
            $sheet->setCellValueByColumnAndRow($col_index++, $row_index, empty($sim_info->denwabango) ? '-' : $sim_info->denwabango);
            // 暗証番号
            $sheet->setCellValueByColumnAndRow($col_index++, $row_index, empty($sim_info->ansyobango) ? '-' : $sim_info->ansyobango);
            // SIMタイプ
            $sheet->setCellValueByColumnAndRow($col_index++, $row_index, $sim_type_list[$sim_info->SIM_TYPE]);
            // 入荷日
            $sheet->setCellValueByColumnAndRow($col_index++, $row_index, $this->date_format($sim_info->ARRIVAL_DATETIME));
            // 出荷状態
            $sheet->setCellValueByColumnAndRow($col_index++, $row_index, $shipment_flag_list[$sim_info->SHIPMENT_FLAG]);
            // 出荷日
            $sheet->setCellValueByColumnAndRow($col_index++, $row_index, $this->date_format($sim_info->SHIPMENT_DATETIME));
            // 納品希望日
            $sheet->setCellValueByColumnAndRow($col_index++, $row_index, $this->date_format($sim_info->DELIVERY_DATE));
            // 出荷先
            $sheet->setCellValueByColumnAndRow($col_index++, $row_index, empty($sim_info->SHIPMENT_DEST) ? '-' : $sim_info->SHIPMENT_DEST);
            // 出荷先2
            $sheet->setCellValueByColumnAndRow($col_index++, $row_index, empty($sim_info->SHIPMENT_DEST2) ? '-' : $sim_info->SHIPMENT_DEST2);
            // MVNO番号
            $sheet->setCellValueByColumnAndRow($col_index++, $row_index, empty($sim_info->MVNO_ID) ? '-' : $sim_info->MVNO_ID);
            // Pool番号
            $sheet->setCellValueByColumnAndRow($col_index++, $row_index, empty($sim_info->POOL_ID) ? '-' : $sim_info->POOL_ID);
            // 半黒化日
            $sheet->setCellValueByColumnAndRow($col_index++, $row_index, $this->date_format($sim_info->HB_DATE));
            // 開通日
            $sheet->setCellValueByColumnAndRow($col_index++, $row_index, $this->date_format($sim_info->OPEN_DATE));
            // 再発行日
            $sheet->setCellValueByColumnAndRow($col_index++, $row_index, $this->date_format($sim_info->REISSUE_DATE));
            // 解約日
            $sheet->setCellValueByColumnAndRow($col_index++, $row_index, $this->date_format($sim_info->CANCELLATION_DATE));
            // 貸出日
            $sheet->setCellValueByColumnAndRow($col_index++, $row_index, $this->date_format($sim_info->LOAN_DATE));
            // RS返却日
            $sheet->setCellValueByColumnAndRow($col_index++, $row_index, $this->date_format($sim_info->RS_RETURN_DATE));
            // ドコモ返却依頼日
            $sheet->setCellValueByColumnAndRow($col_index++, $row_index, $this->date_format($sim_info->DOCOMO_RESULT_REQ_DATE));
            // 料金プラン
            $sheet->setCellValueByColumnAndRow($col_index++, $row_index, empty($sim_info->ryokinplan) ? '-' : $ryokinplan_list[$sim_info->ryokinplan]);
            // 国際電話WORLD　CALL
            $sheet->setCellValueByColumnAndRow($col_index++, $row_index, str_empty($sim_info->WWtukehenhaiFLG) ? '-' : $tukehenhai_list[$sim_info->WWtukehenhaiFLG]);
            // 国際ローミングWORLD　WING
            $sheet->setCellValueByColumnAndRow($col_index++, $row_index, str_empty($sim_info->WCtukehenhaiFLG) ? '-' : $tukehenhai_list[$sim_info->WCtukehenhaiFLG]);
            // 課金情報機能
            $sheet->setCellValueByColumnAndRow($col_index++, $row_index, isset($sousaservice_arr['C0324']) ? $tukehenhai_list[$sousaservice_arr['C0324']] : '-');
            // キャッチホン
            $sheet->setCellValueByColumnAndRow($col_index++, $row_index, isset($sousaservice_arr['C0005']) ? $tukehenhai_list[$sousaservice_arr['C0005']] : '-');
            // 転送でんわ
            $sheet->setCellValueByColumnAndRow($col_index++, $row_index, isset($sousaservice_arr['C0013']) ? $tukehenhai_list[$sousaservice_arr['C0013']] : '-');
            // 国際着信転送
            $sheet->setCellValueByColumnAndRow($col_index++, $row_index, isset($sousaservice_arr['C0007']) ? $tukehenhai_list[$sousaservice_arr['C0007']] : '-');
            // 留守番電話
            $sheet->setCellValueByColumnAndRow($col_index++, $row_index, isset($sousaservice_arr['C0020']) ? $tukehenhai_list[$sousaservice_arr['C0020']] : '-');
            // 仕入価格
            $sheet->setCellValueByColumnAndRow($col_index++, $row_index, '-');
            // 販売価格
            $sheet->setCellValueByColumnAndRow($col_index++, $row_index, '-');
            // マージン
            $sheet->setCellValueByColumnAndRow($col_index++, $row_index, '-');
            // 開通手数料
            $sheet->setCellValueByColumnAndRow($col_index++, $row_index, '-');
            // 備考
            $sheet->setCellValueByColumnAndRow($col_index++, $row_index, '-');
            
        }
        
        // output
        $writer = new Csv($spreadsheet);
        $writer->setUseBOM(true);
        $writer->setDelimiter(',');
        $writer->setEnclosure('');
        $writer->setLineEnding("\r\n");
        $writer->setSheetIndex(0);
        
        // output data
        ob_start();
        $writer->save('php://output');
        $output_data = ob_get_contents();
        ob_end_clean();
        
        echo json_encode(array(
                'success' => TRUE,
                'file_name' => sprintf('在庫表_%s.csv', date_format(date_create(), 'Ymd')),
                'file' => base64_encode($output_data)
        ));
    }
    
    /**
     * date_format
     * @param string $str
     * @param $format
     * @param $default_val : default value when string is empty
     */
    private function date_format(string $str = NULL, string $format = 'Y/m/d', $default_val = '-') 
    {
        if (empty($str)) return $default_val;
        $timestamp = strtotime($str);
        return $timestamp === FALSE ? $default_val : date($format, $timestamp);
    }
    
    /**
     * 削除処理
     */
    public function remove()
    {
        die('Access denied');
        // ajax check
        if (!$this->input->is_ajax_request() || $this->input->method() != 'post')
        {
            die('Access denied');
        }
        
        $this->load->model('sim_inventory_model');
        
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
            // ================= 削除処理を行う ================= //
            if ($this->sim_inventory_model->delete(array('UID' => $uid)) != 1)
            {
                $result_details[$uid] = array(
                        'success'=> false,
                        'message'=> '削除処理が失敗しました。'
                );
                $failed_cnt++;
                continue;
            }
            
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
    
    /**
     * @desc 残っているSIM在庫数を返す
     * @param void
     * @return int 残量数(0 | count)
     */
    public function get_stock_count()
    {
        $this->load->model('sim_inventory_model');
        
        // 残量を返す
        $returned_sim = $this->sim_inventory_model->select_list(array('DELETE_FLAG'=> 0, 'USED_BY_OPEN'=> 0));
        
        echo json_encode(array("count" => count($returned_sim)));
    }
    
}
