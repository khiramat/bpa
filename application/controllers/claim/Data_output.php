<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * 顧客別請求データ出力用コントローラー
 */
class Data_output extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->db = $this->load->database('acs', TRUE);
    }
    /**
     * 顧客別請求データ出力画面
     */
    public function index() {
        $this->load->helper('url');
        for ($i = 0; $i < 6; $i++) {
            $tmp_date = date("Ym");
            $tmp_year = substr($tmp_date, 0, 4);
            $tmp_mon = substr($tmp_date, 4, 2);
            $Yms[] = date('Y-m',mktime(0, 0, 0, $tmp_mon - $i, 1, $tmp_year));
        }
        $data['Ym_list'] = $Yms;
        $sql = "select HEAD_LINE_GROUP_ID ,HEAD_LINE_GROUP_NAME from head_line_group";
        $data['Name_list'] = $this->db->query($sql)->result_array();

        $sqlM = "select MVNO_ID,MVNO_NAME,ADDITIONAL_BUSINESS_CODE from MVNO where DELETE_FLG = 'N'";
        $data['Mvno_list'] = $this->db->query($sqlM)->result_array();


        $simName = $this->input->post('SIM_NAME');
        $simType = $this->input->post('SIM_TYPE');
        $mvnoID = $this->input->post('MVNO');
        if ($simName !== null && $simType !== null) {
            $url = 'index.php/tools/sim/' . $simType . '/' . $simName . '/' . $mvnoID;
            redirect(base_url($url));
        }
        $this->load->view('claim/data_output', $data);
    }

}
