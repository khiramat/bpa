<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * | -------------------------------------------------------------------------
 * | フォームバリデーションルール定義
 * | -------------------------------------------------------------------------
 * |
 */
$config = array (
        // SIM開通-新規申込(通常開通)
        '01-1' => array (
                array (
                        'field' => 'transactionTYPE',
                        'label' => 'トランザクションType',
                        // 'rules' => 'required|alpha_numeric|max_length[3]|min_length[2]',
                        'rules' => 'required' 
                ),
                array (
                        'field' => 'TENANT_ID',
                        'label' => 'テナントID',
                        'rules' => 'required|numeric|exact_length[5]'
                ),
                array (
                        'field' => 'POOL_GROUP',
                        'label' => 'Pool Group選択',
                        'rules' => 'required|max_length[30]'
                ),
                array (
                        'field' => 'LINE_CNT',
                        'label' => '枚数',
                        'rules' => 'required|is_natural_no_zero|max_length[5]'
                ),
                array (
                        'field' => 'cardkeijo',
                        'label' => 'カード形状',
                        'rules' => 'required|numeric|exact_length[1]' 
                ),
                array (
                        'field' => 'ansyobango',
                        'label' => '暗証番号',
                        'rules' => 'trim|numeric|exact_length[4]' 
                ),
                array (
                        'field' => 'ryokinplan',
                        'label' => '料金プラン',
                        'rules' => 'required|alpha_numeric|max_length[5]' 
                ),
                array (
                        'field' => 'sousaservice',
                        'label' => '割引プラン/オプション',
                        'rules' => 'alpha_numeric|min_length[6]' 
                ),
                array (
                        'field' => 'WWtukehenhaiFLG',
                        'label' => 'WW付変廃フラグ',
                        'rules' => 'numeric|exact_length[1]' 
                ),
                array (
                        'field' => 'WWriyouteisimeyasugaku',
                        'label' => 'WW利用停止目安額',
                        'rules' => 'numeric|max_length[8]' 
                ),
                array (
                        'field' => 'WWdaisankokuhassinkisei',
                        'label' => 'WW第三国発信規制',
                        'rules' => 'numeric|exact_length[1]' 
                ),
                array (
                        'field' => 'WCtukehenhaiFLG',
                        'label' => 'WC付変廃フラグ',
                        'rules' => 'numeric|exact_length[1]' 
                ),
                array (
                        'field' => 'WCriyouteisimeyasugaku',
                        'label' => 'WC利用停止目安額',
                        'rules' => 'numeric|max_length[8]' 
                ),
                array (
                        'field' => 'WCtuwateisi',
                        'label' => 'WC通話停止',
                        'rules' => 'numeric|exact_length[1]' 
                ) 
        ),
        
        // SIM開通-新規申込(半黒ROM作成)
        'A2-1' => array (
                array (
                        'field' => 'transactionTYPE',
                        'label' => 'トランザクションType',
                        // 'rules' => 'required|alpha_numeric|max_length[3]|min_length[2]',
                        'rules' => 'required' 
                ),
                array (
                        'field' => 'TENANT_ID',
                        'label' => 'テナントID',
                        'rules' => 'required|numeric|exact_length[5]'
                ),
                array (
                        'field' => 'POOL_GROUP',
                        'label' => 'Pool Group選択',
                        'rules' => 'required|max_length[30]'
                ),
                array (
                        'field' => 'LINE_CNT',
                        'label' => '枚数',
                        'rules' => 'required|is_natural_no_zero|max_length[5]'
                ),
                array (
                        'field' => 'cardkeijo',
                        'label' => 'カード形状',
                        'rules' => 'required|numeric|exact_length[1]' 
                ),
                array (
                        'field' => 'ansyobango',
                        'label' => '暗証番号',
                        'rules' => 'trim|numeric|exact_length[4]' 
                ),
                array (
                        'field' => 'ryokinplan',
                        'label' => '料金プラン',
                        'rules' => 'required|alpha_numeric|max_length[5]' 
                ),
                array (
                        'field' => 'sousaservice',
                        'label' => '割引プラン/オプション',
                        'rules' => 'alpha_numeric|min_length[6]' 
                ),
                array (
                        'field' => 'WWtukehenhaiFLG',
                        'label' => 'WW付変廃フラグ',
                        'rules' => 'numeric|exact_length[1]' 
                ),
                array (
                        'field' => 'WWriyouteisimeyasugaku',
                        'label' => 'WW利用停止目安額',
                        'rules' => 'numeric|max_length[8]' 
                ),
                array (
                        'field' => 'WWdaisankokuhassinkisei',
                        'label' => 'WW第三国発信規制',
                        'rules' => 'numeric|exact_length[1]' 
                ),
                array (
                        'field' => 'WCtukehenhaiFLG',
                        'label' => 'WC付変廃フラグ',
                        'rules' => 'numeric|exact_length[1]' 
                ),
                array (
                        'field' => 'WCriyouteisimeyasugaku',
                        'label' => 'WC利用停止目安額',
                        'rules' => 'numeric|max_length[8]' 
                ),
                array (
                        'field' => 'WCtuwateisi',
                        'label' => 'WC通話停止',
                        'rules' => 'numeric|exact_length[1]' 
                ) 
        ),
        
        // SIM開通-新規申込(半黒ROM⇒開通)
        'M02-1' => array (
                array (
                        'field' => 'transactionTYPE',
                        'label' => 'トランザクションType',
                        // 'rules' => 'required|alpha_numeric|max_length[3]|min_length[2]',
                        'rules' => 'required' 
                ),
                array (
                        'field' => 'TENANT_ID',
                        'label' => 'テナントID',
                        'rules' => 'required|numeric|exact_length[5]'
                ),
                array (
                        'field' => 'denwabango',
                        'label' => '電話番号',
                        'rules' => 'trim|required|valid_tel' 
                ) 
        ),
        
        // SIM開通-MNP転入受付(通常開通)
        '01-2' => array (
                array (
                        'field' => 'transactionTYPE',
                        'label' => 'トランザクションType',
                        // 'rules' => 'required|alpha_numeric|max_length[3]|min_length[2]',
                        'rules' => 'required' 
                ),
                array (
                        'field' => 'TENANT_ID',
                        'label' => 'テナントID',
                        'rules' => 'required|numeric|exact_length[5]'
                ),
                array (
                        'field' => 'POOL_GROUP',
                        'label' => 'Pool Group選択',
                        'rules' => 'required|max_length[30]'
                ),
                array (
                        'field' => 'denwabango',
                        'label' => '電話番号',
                        'rules' => 'trim|required|valid_tel' 
                ),
                array (
                        'field' => 'cardkeijo',
                        'label' => 'カード形状',
                        'rules' => 'required|numeric|exact_length[1]' 
                ),
                array (
                        'field' => 'MNPyoyakubango',
                        'label' => 'MNP予約番号',
                        'rules' => 'trim|required|numeric|exact_length[10]' 
                ),
                array (
                        'field' => 'MNPzokusei',
                        'label' => 'MNP属性',
                        'rules' => 'required|numeric|exact_length[1]' 
                ),
                array (
                        'field' => 'MNPyoyakusyakana',
                        'label' => 'MNP予約者名カナ',
                        'rules' => 'trim|required|hankaku_katakana|max_length[50]' 
                ),
                array (
                        'field' => 'MNPyoyakusyakanji',
                        'label' => 'MNP予約者名漢字',
                        'rules' => 'trim|required|zenkaku|max_length[50]' 
                ),
                array (
                        'field' => 'MNPseinengappi',
                        'label' => 'MNP生年月日',
                        'rules' => 'remove_slash|callback_seinengappi_check[MNPzokusei]|valid_date[Ymd]' 
                ),
                array (
                        'field' => 'ansyobango',
                        'label' => '暗証番号',
                        'rules' => 'trim|numeric|exact_length[4]' 
                ),
                array (
                        'field' => 'ryokinplan',
                        'label' => '料金プラン',
                        'rules' => 'required|alpha_numeric|max_length[5]' 
                ),
                array (
                        'field' => 'sousaservice',
                        'label' => '割引プラン/オプション',
                        'rules' => 'alpha_numeric|min_length[6]' 
                ),
                array (
                        'field' => 'WWtukehenhaiFLG',
                        'label' => 'WW付変廃フラグ',
                        'rules' => 'numeric|exact_length[1]' 
                ),
                array (
                        'field' => 'WWriyouteisimeyasugaku',
                        'label' => 'WW利用停止目安額',
                        'rules' => 'numeric|max_length[8]' 
                ),
                array (
                        'field' => 'WWdaisankokuhassinkisei',
                        'label' => 'WW第三国発信規制',
                        'rules' => 'numeric|exact_length[1]' 
                ),
                array (
                        'field' => 'WCtukehenhaiFLG',
                        'label' => 'WC付変廃フラグ',
                        'rules' => 'numeric|exact_length[1]' 
                ),
                array (
                        'field' => 'WCriyouteisimeyasugaku',
                        'label' => 'WC利用停止目安額',
                        'rules' => 'numeric|max_length[8]' 
                ),
                array (
                        'field' => 'WCtuwateisi',
                        'label' => 'WC通話停止',
                        'rules' => 'numeric|exact_length[1]' 
                ) 
        ),
        
        // SIM開通-MNP転入受付(半黒ROM作成)
        'A2-2' => array (
                array (
                        'field' => 'transactionTYPE',
                        'label' => 'トランザクションType',
                        // 'rules' => 'required|alpha_numeric|max_length[3]|min_length[2]',
                        'rules' => 'required' 
                ),
                array (
                        'field' => 'TENANT_ID',
                        'label' => 'テナントID',
                        'rules' => 'required|numeric|exact_length[5]'
                ),
                array (
                        'field' => 'POOL_GROUP',
                        'label' => 'Pool Group選択',
                        'rules' => 'required|max_length[30]'
                ),
                array (
                        'field' => 'denwabango',
                        'label' => '電話番号',
                        'rules' => 'trim|required|valid_tel' 
                ),
                array (
                        'field' => 'cardkeijo',
                        'label' => 'カード形状',
                        'rules' => 'required|numeric|exact_length[1]' 
                ),
                array (
                        'field' => 'MNPyoyakubango',
                        'label' => 'MNP予約番号',
                        'rules' => 'trim|required|numeric|exact_length[10]' 
                ),
                array (
                        'field' => 'MNPzokusei',
                        'label' => 'MNP属性',
                        'rules' => 'required|numeric|exact_length[1]' 
                ),
                array (
                        'field' => 'MNPyoyakusyakana',
                        'label' => 'MNP予約者名カナ',
                        'rules' => 'trim|required|hankaku_katakana|max_length[50]' 
                ),
                array (
                        'field' => 'MNPyoyakusyakanji',
                        'label' => 'MNP予約者名漢字',
                        'rules' => 'trim|required|zenkaku|max_length[50]' 
                ),
                array (
                        'field' => 'MNPseinengappi',
                        'label' => 'MNP生年月日',
                        'rules' => 'remove_slash|callback_seinengappi_check[MNPzokusei]|valid_date[Ymd]' 
                ),
                array (
                        'field' => 'ansyobango',
                        'label' => '暗証番号',
                        'rules' => 'trim|numeric|exact_length[4]' 
                ),
                array (
                        'field' => 'ryokinplan',
                        'label' => '料金プラン',
                        'rules' => 'required|alpha_numeric|max_length[5]' 
                ),
                array (
                        'field' => 'sousaservice',
                        'label' => '割引プラン/オプション',
                        'rules' => 'alpha_numeric|min_length[6]' 
                ),
                array (
                        'field' => 'WWtukehenhaiFLG',
                        'label' => 'WW付変廃フラグ',
                        'rules' => 'numeric|exact_length[1]' 
                ),
                array (
                        'field' => 'WWriyouteisimeyasugaku',
                        'label' => 'WW利用停止目安額',
                        'rules' => 'numeric|max_length[8]' 
                ),
                array (
                        'field' => 'WWdaisankokuhassinkisei',
                        'label' => 'WW第三国発信規制',
                        'rules' => 'numeric|exact_length[1]' 
                ),
                array (
                        'field' => 'WCtukehenhaiFLG',
                        'label' => 'WC付変廃フラグ',
                        'rules' => 'numeric|exact_length[1]' 
                ),
                array (
                        'field' => 'WCriyouteisimeyasugaku',
                        'label' => 'WC利用停止目安額',
                        'rules' => 'numeric|max_length[8]' 
                ),
                array (
                        'field' => 'WCtuwateisi',
                        'label' => 'WC通話停止',
                        'rules' => 'numeric|exact_length[1]' 
                ) 
        ),
        
        // SIM開通-MNP転入受付(半黒ROM⇒開通)
        'M02-2' => array (
                array (
                        'field' => 'transactionTYPE',
                        'label' => 'トランザクションType',
                        // 'rules' => 'required|alpha_numeric|max_length[3]|min_length[2]',
                        'rules' => 'required' 
                ),
                array (
                        'field' => 'TENANT_ID',
                        'label' => 'テナントID',
                        'rules' => 'required|numeric|exact_length[5]'
                ),
                array (
                        'field' => 'denwabango',
                        'label' => '電話番号',
                        'rules' => 'trim|required|valid_tel' 
                ),
                array (
                        'field' => 'MNPyoyakubango',
                        'label' => 'MNP予約番号',
                        'rules' => 'trim|required|numeric|exact_length[10]' 
                ),
                array (
                        'field' => 'MNPzokusei',
                        'label' => 'MNP属性',
                        'rules' => 'required|numeric|exact_length[1]' 
                ),
                array (
                        'field' => 'MNPyoyakusyakana',
                        'label' => 'MNP予約者名カナ',
                        'rules' => 'trim|required|hankaku_katakana|max_length[50]' 
                ),
                array (
                        'field' => 'MNPyoyakusyakanji',
                        'label' => 'MNP予約者名漢字',
                        'rules' => 'trim|required|zenkaku|max_length[50]' 
                ),
                array (
                        'field' => 'MNPseinengappi',
                        'label' => 'MNP生年月日',
                        'rules' => 'remove_slash|callback_seinengappi_check[MNPzokusei]|valid_date[Ymd]' 
                ) 
        ),
        
        // SIM開通-カード再発行(白ROM未開通⇒開通)
        'M02-3' => array (
                array (
                        'field' => 'transactionTYPE',
                        'label' => 'トランザクションType',
                        // 'rules' => 'required|alpha_numeric|max_length[3]|min_length[2]',
                        'rules' => 'required' 
                ),
                array (
                        'field' => 'TENANT_ID',
                        'label' => 'テナントID',
                        'rules' => 'required|numeric|exact_length[5]'
                ),
                array (
                        'field' => 'denwabango',
                        'label' => '電話番号',
                        'rules' => 'trim|required|valid_tel' 
                ) 
        ),
        
        // SIM変更-サービス変更
        '02-1' => array (
                array (
                        'field' => 'transactionTYPE',
                        'label' => 'トランザクションType',
                        // 'rules' => 'required|alpha_numeric|max_length[3]|min_length[2]',
                        'rules' => 'required' 
                ),
                array (
                        'field' => 'TENANT_ID',
                        'label' => 'テナントID',
                        'rules' => 'required|numeric|exact_length[5]'
                ),
                array (
                        'field' => 'denwabango',
                        'label' => '電話番号',
                        'rules' => 'trim|required|valid_tel' 
                ),
                array (
                        'field' => 'ansyobango',
                        'label' => '暗証番号',
                        'rules' => 'trim|numeric|exact_length[4]' 
                ),
                array (
                        'field' => 'ryokinplan',
                        'label' => '料金プラン',
                        'rules' => 'alpha_numeric|max_length[5]' 
                ),
                array (
                        'field' => 'sousaservice',
                        'label' => '割引プラン/オプション',
                        'rules' => 'alpha_numeric|min_length[6]' 
                ),
                array (
                        'field' => 'WWtukehenhaiFLG',
                        'label' => 'WW付変廃フラグ',
                        'rules' => 'numeric|exact_length[1]' 
                ),
                array (
                        'field' => 'WWriyouteisimeyasugaku',
                        'label' => 'WW利用停止目安額',
                        'rules' => 'numeric|max_length[8]' 
                ),
                array (
                        'field' => 'WWdaisankokuhassinkisei',
                        'label' => 'WW第三国発信規制',
                        'rules' => 'numeric|exact_length[1]' 
                ),
                array (
                        'field' => 'WCtukehenhaiFLG',
                        'label' => 'WC付変廃フラグ',
                        'rules' => 'numeric|exact_length[1]' 
                ),
                array (
                        'field' => 'WCriyouteisimeyasugaku',
                        'label' => 'WC利用停止目安額',
                        'rules' => 'numeric|max_length[8]' 
                ),
                array (
                        'field' => 'WCtuwateisi',
                        'label' => 'WC通話停止',
                        'rules' => 'numeric|exact_length[1]' 
                ) 
        ),
        
        // SIM変更-カード再発行
        '02-2' => array (
                array (
                        'field' => 'transactionTYPE',
                        'label' => 'トランザクションType',
                        // 'rules' => 'required|alpha_numeric|max_length[3]|min_length[2]',
                        'rules' => 'required' 
                ),
                array (
                        'field' => 'TENANT_ID',
                        'label' => 'テナントID',
                        'rules' => 'required|numeric|exact_length[5]'
                ),
                array (
                        'field' => 'denwabango',
                        'label' => '電話番号',
                        'rules' => 'trim|required|valid_tel' 
                ),
                array (
                        'field' => 'cardkeijo',
                        'label' => 'カード形状',
                        'rules' => 'required|numeric|exact_length[1]' 
                ),
                array (
                        'field' => 'ansyobango',
                        'label' => '暗証番号',
                        'rules' => 'trim|numeric|exact_length[4]' 
                ),
                array (
                        'field' => 'ryokinplan',
                        'label' => '料金プラン',
                        'rules' => 'alpha_numeric|max_length[5]' 
                ),
                array (
                        'field' => 'sousaservice',
                        'label' => '割引プラン/オプション',
                        'rules' => 'alpha_numeric|min_length[6]' 
                ),
                array (
                        'field' => 'WWtukehenhaiFLG',
                        'label' => 'WW付変廃フラグ',
                        'rules' => 'numeric|exact_length[1]' 
                ),
                array (
                        'field' => 'WWriyouteisimeyasugaku',
                        'label' => 'WW利用停止目安額',
                        'rules' => 'numeric|max_length[8]' 
                ),
                array (
                        'field' => 'WWdaisankokuhassinkisei',
                        'label' => 'WW第三国発信規制',
                        'rules' => 'numeric|exact_length[1]' 
                ),
                array (
                        'field' => 'WCtukehenhaiFLG',
                        'label' => 'WC付変廃フラグ',
                        'rules' => 'numeric|exact_length[1]' 
                ),
                array (
                        'field' => 'WCriyouteisimeyasugaku',
                        'label' => 'WC利用停止目安額',
                        'rules' => 'numeric|max_length[8]' 
                ),
                array (
                        'field' => 'WCtuwateisi',
                        'label' => 'WC通話停止',
                        'rules' => 'numeric|exact_length[1]' 
                ) 
        ),
        
        // SIM変更-利用中断・再開
        '21-1' => array (
                array (
                        'field' => 'transactionTYPE',
                        'label' => 'トランザクションType',
                        // 'rules' => 'required|alpha_numeric|max_length[3]|min_length[2]',
                        'rules' => 'required' 
                ),
                array (
                        'field' => 'TENANT_ID',
                        'label' => 'テナントID',
                        'rules' => 'required|numeric|exact_length[5]'
                ),
                array (
                        'field' => 'denwabango',
                        'label' => '電話番号',
                        'rules' => 'trim|required|valid_tel' 
                ) 
        ),
        
        // SIM変更-MNP予約
        'A5-1' => array (
                array (
                        'field' => 'transactionTYPE',
                        'label' => 'トランザクションType',
                        // 'rules' => 'required|alpha_numeric|max_length[3]|min_length[2]',
                        'rules' => 'required' 
                ),
                array (
                        'field' => 'TENANT_ID',
                        'label' => 'テナントID',
                        'rules' => 'required|numeric|exact_length[5]'
                ),
                array (
                        'field' => 'denwabango',
                        'label' => '電話番号',
                        'rules' => 'trim|required|valid_tel' 
                ),
                array (
                        'field' => 'MNPzokusei',
                        'label' => 'MNP属性',
                        'rules' => 'required|numeric|exact_length[1]' 
                ),
                array (
                        'field' => 'MNPyoyakusyakana',
                        'label' => 'MNP予約者名カナ',
                        'rules' => 'trim|required|hankaku_katakana|max_length[50]' 
                ),
                array (
                        'field' => 'MNPyoyakusyakanji',
                        'label' => 'MNP予約者名漢字',
                        'rules' => 'trim|required|zenkaku|max_length[50]' 
                ),
                array (
                        'field' => 'MNPseinengappi',
                        'label' => 'MNP生年月日',
                        'rules' => 'remove_slash|callback_seinengappi_check[MNPzokusei]|valid_date[Ymd]' 
                ) 
        ),
        
        // SIM変更-MNP予約解除
        'A6-1' => array (
                array (
                        'field' => 'transactionTYPE',
                        'label' => 'トランザクションType',
                        // 'rules' => 'required|alpha_numeric|max_length[3]|min_length[2]',
                        'rules' => 'required' 
                ),
                array (
                        'field' => 'TENANT_ID',
                        'label' => 'テナントID',
                        'rules' => 'required|numeric|exact_length[5]'
                ),
                array (
                        'field' => 'denwabango',
                        'label' => '電話番号',
                        'rules' => 'trim|required|valid_tel' 
                ) 
        ),
        
        // SIM変更-解約
        '49-1' => array (
                array (
                        'field' => 'transactionTYPE',
                        'label' => 'トランザクションType',
                        // 'rules' => 'required|alpha_numeric|max_length[3]|min_length[2]',
                        'rules' => 'required' 
                ),
                array (
                        'field' => 'TENANT_ID',
                        'label' => 'テナントID',
                        'rules' => 'required|numeric|exact_length[5]'
                ),
                array (
                        'field' => 'denwabango',
                        'label' => '電話番号',
                        'rules' => 'trim|required|valid_tel' 
                ) 
        ),
        
        // SIM変更-検索-MNP可否照会(kensakukoumoku == 1)
        'M01-1' => array (
                array (
                        'field' => 'transactionTYPE',
                        'label' => 'トランザクションType',
                        // 'rules' => 'required|alpha_numeric|max_length[3]|min_length[2]',
                        'rules' => 'required' 
                ),
                array (
                        'field' => 'TENANT_ID',
                        'label' => 'テナントID',
                        'rules' => 'required|numeric|exact_length[5]'
                ),
                array (
                        'field' => 'kensakukoumoku',
                        'label' => '検索項目',
                        'rules' => 'required|exact_length[1]' 
                ),
                array (
                        'field' => 'denwabango',
                        'label' => '電話番号',
                        'rules' => 'trim|required|valid_tel' 
                ),
                array (
                        'field' => 'MNPyoyakubango',
                        'label' => 'MNP予約番号',
                        'rules' => 'trim|required|numeric|exact_length[10]' 
                ),
                array (
                        'field' => 'MNPzokusei',
                        'label' => 'MNP属性',
                        'rules' => 'required|numeric|exact_length[1]' 
                ),
                array (
                        'field' => 'MNPyoyakusyakana',
                        'label' => 'MNP予約者名カナ',
                        'rules' => 'trim|required|hankaku_katakana|max_length[50]' 
                ),
                array (
                        'field' => 'MNPyoyakusyakanji',
                        'label' => 'MNP予約者名漢字',
                        'rules' => 'trim|required|zenkaku|max_length[50]' 
                ),
                array (
                        'field' => 'MNPseinengappi',
                        'label' => 'MNP生年月日',
                        'rules' => 'remove_slash|callback_seinengappi_check[MNPzokusei]|valid_date[Ymd]' 
                ),
                array (
                        'field' => 'ryokinplan',
                        'label' => '料金プラン',
                        'rules' => 'required|alpha_numeric|max_length[5]' 
                ) 
        ),
        
        // SIM変更-検索-WW累積額検索/WC累積額検索(kensakukoumoku == 2 or 3)
        'M01-23' => array (
                array (
                        'field' => 'transactionTYPE',
                        'label' => 'トランザクションType',
                        // 'rules' => 'required|alpha_numeric|max_length[3]|min_length[2]',
                        'rules' => 'required' 
                ),
                array (
                        'field' => 'TENANT_ID',
                        'label' => 'テナントID',
                        'rules' => 'required|numeric|exact_length[5]'
                ),
                array (
                        'field' => 'kensakukoumoku',
                        'label' => '検索項目',
                        'rules' => 'required|exact_length[1]' 
                ),
                array (
                        'field' => 'denwabango',
                        'label' => '電話番号',
                        'rules' => 'trim|required|valid_tel' 
                ) 
        ),
        
        // SIM依頼 - 新規入力 - アップロード
        'acs/input/upload' => array (
                array (
                        'field' => 'TENANT_ID',
                        'label' => 'テナントID',
                        'rules' => 'htmlspecialchars|trim|required|numeric|exact_length[5]',
                ),
                array (
                        'field' => 'ryokinplan',
                        'label' => '料金プラン',
                        'rules' => 'htmlspecialchars|trim|required|alpha_numeric|max_length[5]'
                ),
                array (
                        'field' => 'cardkeijo',
                        'label' => 'カード形状',
                        'rules' => 'htmlspecialchars|trim|required|numeric|exact_length[1]'
                ),
                array (
                        'field' => 'transactionTYPE',
                        'label' => 'トランザクションType',
                        'rules' => 'htmlspecialchars|trim|required|alpha_numeric|max_length[3]|min_length[2]',
                ),
                array (
                        'field' => 'LINE_CNT',
                        'label' => '枚数',
                        'rules' => 'htmlspecialchars|trim|numeric|max_length[6]',
                ),
                array (
                        'field' => 'denwabango',
                        'label' => '電話番号',
                        'rules' => 'htmlspecialchars|trim|valid_tel'
                ),
                array (
                        'field' => 'sousaservice',
                        'label' => '割引オプション',
                        'rules' => 'htmlspecialchars|trim|alpha_numeric|min_length[6]',
                ),
        ),
        
        // インベントリ管理 - SIM入庫処理
        'inventory/sim_storage/save' => array (
                array (
                        'field' => 'SIM_TYPE',
                        'label' => 'SIMタイプ',
                        'rules' => 'htmlspecialchars|trim|required|numeric|exact_length[1]',
                ),
                array (
                        'field' => 'SEIZOBANGO_STR',
                        'label' => '読込結果',
                        'rules' => 'htmlspecialchars|trim|required|min_length[15]'
                ),
        ),

);