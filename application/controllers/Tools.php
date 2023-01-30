<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Tools extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->db = $this->load->database('acs', TRUE);
    }

    public function sim($group = null, $dateSelect = null, $mvno = null) {
        try {
            $name = "sim_all_group";
            $groupList = $this->group($group);
            $groupLineIdList = [];
            if ($groupList !== null) {
                $name = $groupList["HEAD_LINE_GROUP_NAME"];
                $groupId = $groupList["HEAD_LINE_GROUP_ID"];
                $sqlG = "select HEAD_LINE_ID from head_line where head_line_group_id = $groupId";
                $groupLineIdList = $this->db->query($sqlG)->result_array();
            }

            $sqlA='';
            foreach ($groupLineIdList as $o => $groupLineId) {
                $headLID = $groupLineId["HEAD_LINE_ID"];

                    if ($o == 0) {
                        $sqlA .= $headLID;
                    }
                    $sqlA .= ' or HEAD_LINE_ID = ' . $headLID . '';
            }

            $sql = "select LINE_ID,START_DATE,REORGANIZE_DATE,DELETE_FLG from LINE where "
                    . "SIM_NUM IS NOT NULL and START_DATE IS NOT NULL and START_DATE !='' "
                    . " and MVNO_ID = $mvno and HEAD_LINE_ID = $sqlA";
            $simList = $this->db->query($sql)->result_array();
            foreach($simList as $e=>$sL){
                if($sL['START_DATE'] = $sL['REORGANIZE_DATE']){
                    $simList[$e]['REORGANIZE_DATE']=date('Ymd');
                }
            }
            
            //line_plan
            $sqlLP = "select CD  from line_plan where HEAD_LINE_ID =$groupId";
            $linePlan = $this->db->query($sqlLP)->result_array();

            //mvno
            $sqlM = "select MVNO_NAME from MVNO where MVNO_ID = $mvno";
            $mvnoName = $this->db->query($sqlM)->result_array();

            // csvフェイル設定
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename=' . $dateSelect . "_" . $name . "_" . $mvnoName[0]['MVNO_NAME'] . ".csv");
            header('Cache-Control: max-age=0');
            $fp = fopen('php://output', 'a');

            // csv列名
            echo chr(0xEF) . chr(0xBB) . chr(0xBF);

            if ($simList) {
                //今月一日
            //今月末日
                if ($dateSelect) {
                    $firstDay = date('Y年m月01日', strtotime($dateSelect));
                    $lastDay = date('Y年m月d日', strtotime(date('Y-m-01', strtotime(date("$dateSelect-01"))) . "+1 month -1 day"));
                } else {
                    $firstDay = date('Y年m月01日');
                    $lastDay = date('Y年m月d日', strtotime(date('Y-m-01', strtotime(date("Y-m-d"))) . "+1 month -1 day"));
                }

                $simNo = 0;
                $reorganizeArr = [];
                foreach ($simList as $simData) {
                    //開始日
                    $simStartDate = $simData['START_DATE'];
                    $simEndDate = $simData['REORGANIZE_DATE'];
                    $startArr = date_parse_from_format('Y年m月d日', $firstDay);
                    $timeStart = mktime(0, 0, 0, $startArr['month'], $startArr['day'], $startArr['year']);
                    $arr = date_parse_from_format('Y年m月d日', $lastDay);
                    $lastStart = mktime(0, 0, 0, $arr['month'], $arr['day'], $arr['year']);
                    if (strtotime($simStartDate) <= $timeStart && strtotime($simEndDate) >= $lastStart && $simData['DELETE_FLG'] = 'N') { 
                        //SIM 枚数 DB       
                        $simNo++;
                    } else {
                        if (strtotime($simEndDate) <= $lastStart && strtotime($simEndDate) >= $timeStart && (mktime(0, 0, 0, date('m'), date('d') + 1, date('Y')) - 1) >= strtotime($simStartDate)) {
                            $reorganizeArr[] = $simStartDate . $simEndDate;
                        }
                    }
                }
                $head = ["項目", "数量", "単位", "単価", "金額"];

                //SIM 月単価
                if ($linePlan) {
                    $simMoney = $this->config->item($linePlan[0]['CD']);
                } else {
                    $simMoney = $this->config->item('month');
                }

                //SIM総価
                $simAllMoney = $simNo * $simMoney;

                $simTitle = ["■SIM基本使用料"];
                $simMonth = ["◆月額 :" . $firstDay . "～" . $lastDay, number_format($simNo), "回線", number_format($simMoney), number_format($simAllMoney)];

                $list = [$head, $simTitle, $simMonth];
                // csv file put
                foreach ($list as $fields) {
                    fputcsv($fp, $fields);
                }
                //今月途中解約
                $simDayTotalMoneyMiddle = 0;
                if (count($reorganizeArr) > 0) {
                    foreach ($reorganizeArr as $key => $reorganizeC) {
                        $simBeginD = strtotime(substr($reorganizeC, 0, 8));
                        if ($simBeginD < $timeStart) {
                            $reorganizeArr[$key] = str_replace(substr($reorganizeC, 0, 8), date('Ymd', $timeStart), $reorganizeC);
                        }
                    }

                    $reorganizes = array_count_values($reorganizeArr);
                    foreach ($reorganizes as $reorganize => $rsNo) {
                        $simBeginDate = strtotime(substr($reorganize, 0, 8));
                        $simEndDate = strtotime(substr($reorganize, -8));

                        $simStartDateOutPut = date('Y年m月d日', $simBeginDate);
                        $simEndDateOutPut = date('Y年m月d日', $simEndDate);
                        $useSimDay = round(($simEndDate - $simBeginDate) / 3600 / 24) + 1;
                        $simDayPrice = round($simMoney / date('t') * $useSimDay);
                        $simWPrice = $simDayPrice * $rsNo;
                        $simDay = ["◆日割り:" . $simStartDateOutPut . "～" . $simEndDateOutPut, $rsNo, "回線", number_format($simDayPrice), number_format($simWPrice)];
                        $simDayTotalMoneyMiddle += $simWPrice;
                        fputcsv($fp, $simDay);
                    }
                }

                //総価
                $allPrice = $simDayTotalMoneyMiddle + $simAllMoney;
                //消費税
                $zeiritsu = $this->config->item('tax');

                //tax 
                $taxPrice = round($allPrice * $zeiritsu / 100);
                $totalPrice = $taxPrice + $allPrice;

                $allMoney = ["小計", number_format($allPrice)];
                $tax = ["消費税", number_format($taxPrice)];
                $totalMoney = ["合計金額", number_format($totalPrice)];
                $list = [[], $allMoney, $tax, $totalMoney];
                // csv file put
                foreach ($list as $fields) {
                    fputcsv($fp, $fields);
                }
                Fclose($fp);
            } else {
                $head = ["項目", "数量", "単位", "単価", "金額"];
                $simTitle = ["■SIM基本使用料"];
                $allMoney = ["小計", "0"];
                $tax = ["消費税", "0"];
                $totalMoney = ["合計金額", "0"];
                $list = [$head, $simTitle, [], $allMoney, $tax, $totalMoney];
                foreach ($list as $fields) {
                    fputcsv($fp, $fields);
                }
                Fclose($fp);
            }
        } catch (Exception $e) {
            print $e->getMessage();
            exit();
        }
    }

    public function sim_list($group = null) {
        //file名
        $fileName = "sim_list_all_group";
        $groupList = $this->group($group);
        if ($groupList !== null) {
            $fileName = $groupList["HEAD_LINE_GROUP_NAME"];
            $groupId = $groupList["HEAD_LINE_GROUP_ID"];
        }



        $fileName = "sim_list_all_group";
        $groupList = $this->group($group);
        if ($groupList !== null) {
            $fileName = "list_" . $groupList["HEAD_LINE_GROUP_NAME"];
            $groupId = $groupList["HEAD_LINE_GROUP_ID"];
        }

        $simList = [];
        if ($groupList == null) {
            $sql = "select LINE_NUM,START_DATE,REORGANIZE_DATE from LINE where DELETE_FLG = 'N' and SIM_NUM IS NOT NULL and START_DATE IS NOT NULL and START_DATE !=''";
        } else {
            $sql = "select LINE_NUM,START_DATE,REORGANIZE_DATE from LINE where DELETE_FLG = 'N' and SIM_NUM IS NOT NULL and START_DATE IS NOT NULL and START_DATE !='' and HEAD_LINE_ID =$groupId";
        }
        $simList = $this->db->query($sql)->result_array();

        $simLists = $this->db->query($sql)->result_array();
        // csvフェイル設定
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename=' . date("Y年m月d日") . "_" . $fileName . ".csv");
        header('Cache-Control: max-age=0');



        //SIM 月単価
        $simMoney = $this->config->item('sim_month_price');

        $fp = fopen('php://output', 'a');
        // csv列名
        echo chr(0xEF) . chr(0xBB) . chr(0xBF);
        $no = 0;
        if ($simLists) {
            $head = ["項番", "期間", "回線番号", "数量", "単位", "単価", "単位"];
            fputcsv($fp, $head);
            foreach ($simLists as $simList) {
                $startDate = $simList ["START_DATE"];
                $endDate = $simList["REORGANIZE_DATE"];
                $tel = $simList["LINE_NUM"];
                $lastDay = date('Y年m月d日', strtotime(date('Y-m-01', strtotime(date("Y-m-d"))) . "+1 month -1 day"));
                if (strtotime($startDate) <= strtotime(date('Ym01')) && strtotime($endDate) > strtotime(date('Y-m-01', strtotime(date("Y-m-d"))) . "+1 month -1 day")) {
                    $no++;
                    $lineNum = substr($tel, 0, strlen($tel) - 8) . "-" . substr($tel, -8, 4) . "-" . substr($tel, -4);
                    $period = date('Y年m月01日') . "～" . $lastDay;
                    $simData = [$no, $period, $lineNum, "1", "枚", number_format($simMoney), "円"];
                    fputcsv($fp, $simData);
                }
            }
        } else {
            die("0 件");
        }
        Fclose($fp);
    }

    public function group($group) {

        $groupList = [];
        $sqlGroup = "select HEAD_LINE_GROUP_ID,HEAD_LINE_GROUP_NAME from HEAD_LINE_GROUP where DELETE_FLG = 'N'";
        $groupLists = $this->db->query($sqlGroup)->result_array();

        foreach ($groupLists as $groupList) {
            if ($groupList['HEAD_LINE_GROUP_ID'] == $group) {
                $groupData = [
                    'HEAD_LINE_GROUP_NAME' => $groupList['HEAD_LINE_GROUP_NAME'],
                    'HEAD_LINE_GROUP_ID' => $groupList['HEAD_LINE_GROUP_ID']
                ];
                break;
            }
        }
        if (!isset($groupData)) {
            $groupData = null;
        }
        return $groupData;
    }

}
