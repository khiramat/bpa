<?php
defined('BASEPATH') or exit('No direct script access allowed');
/**
 * フォームの定義値 $property[$acs, $inventory, $invoce, $analytics]
 */

/**
 * acsのform属性定義
 *
 * @param
 *        new or edit
 * @return array : property['acs']['new'|'edit']['属性’]
 * @author jhsong
 */
if (!function_exists('get_acs_property')) {
	
	function get_acs_property($type = 'open or modify')
	{
		if ($type == 'open') {
			$acs = array(
				'01-1'  => array(
					'TENANT_ID'               => '*',
					'POOL_GROUP'              => '*', // テナントIDとPOOL_TYPEによって、選択肢が変わる
					'LINE_CNT'                => '*',
					'cardkeijo'               => '*',
					'ansyobango'              => '▲',
					'ryokinplan'              => '*',
					'contracttype'            => '*',
					'sousaservice'            => '▲',
					'WWtukehenhaiFLG'         => '▲',
					'WWriyouteisimeyasugaku'  => '▲',
					'WWdaisankokuhassinkisei' => '▲',
					'WCtukehenhaiFLG'         => '▲',
					'WCriyouteisimeyasugaku'  => '▲',
					'WCtuwateisi'             => '▲'
				),
				'01-2'  => array(
					'TENANT_ID'               => '*',
					'POOL_GROUP'              => '*',
					'denwabango'              => '*',
					'cardkeijo'               => '*',
					'ryokinplan'              => '*',
					'contracttype'            => '*',
					'MNPyoyakubango'          => '*',
					'MNPzokusei'              => '*',
					'MNPyoyakusyakana'        => '*',
					'MNPyoyakusyakanji'       => '*',
					'MNPseinengappi'          => '#',
					'ansyobango'              => '▲',
					'sousaservice'            => '▲',
					'WWtukehenhaiFLG'         => '▲',
					'WWriyouteisimeyasugaku'  => '▲',
					'WWdaisankokuhassinkisei' => '▲',
					'WCtukehenhaiFLG'         => '▲',
					'WCriyouteisimeyasugaku'  => '▲',
					'WCtuwateisi'             => '▲'
				),
				'A2-1'  => array(
					'TENANT_ID'               => '*',
					'POOL_GROUP'              => '*',
					'LINE_CNT'                => '*',
					'cardkeijo'               => '*',
					'ryokinplan'              => '*',
					'contracttype'            => '*',
					'ansyobango'              => '▲',
					'sousaservice'            => '▲',
					'WWtukehenhaiFLG'         => '▲',
					'WWriyouteisimeyasugaku'  => '▲',
					'WWdaisankokuhassinkisei' => '▲',
					'WCtukehenhaiFLG'         => '▲',
					'WCriyouteisimeyasugaku'  => '▲',
					'WCtuwateisi'             => '▲'
				),
				'A2-2'  => array(
					'TENANT_ID'               => '*',
					'POOL_GROUP'              => '*',
					'denwabango'              => '*',
					'cardkeijo'               => '*',
					'ryokinplan'              => '*',
					'contracttype'            => '*',
					'MNPyoyakubango'          => '*',
					'MNPzokusei'              => '*',
					'MNPyoyakusyakana'        => '*',
					'MNPyoyakusyakanji'       => '*',
					'MNPseinengappi'          => '#',
					'ansyobango'              => '▲',
					'sousaservice'            => '▲',
					'WWtukehenhaiFLG'         => '▲',
					'WWriyouteisimeyasugaku'  => '▲',
					'WWdaisankokuhassinkisei' => '▲',
					'WCtukehenhaiFLG'         => '▲',
					'WCriyouteisimeyasugaku'  => '▲',
					'WCtuwateisi'             => '▲'
				),
				'M02-1' => array(
					'TENANT_ID'  => '*',
					'denwabango' => '*'
				),
				'M02-2' => array(
					'TENANT_ID'         => '*',
					'denwabango'        => '*',
					'MNPyoyakubango'    => '*',
					'MNPzokusei'        => '*',
					'MNPyoyakusyakana'  => '*',
					'MNPyoyakusyakanji' => '*',
					'MNPseinengappi'    => '#'
				),
				'M02-3' => array(
					'TENANT_ID'  => '*',
					'denwabango' => '*'
				)
			);
		} else // editの場合
		{
			$acs = array(
				'02-1'  => array(
					'TENANT_ID'               => '*',
					'denwabango'              => '*',
					'ansyobango'              => '▲',
					'ryokinplan'              => '▲',
					'contracttype'            => '▲',
					'sousaservice'            => '▲',
					'WWtukehenhaiFLG'         => '▲',
					'WWriyouteisimeyasugaku'  => '▲',
					'WWdaisankokuhassinkisei' => '▲',
					'WCtukehenhaiFLG'         => '▲',
					'WCriyouteisimeyasugaku'  => '▲',
					'WCtuwateisi'             => '▲'
				),
				'02-2'  => array(
					'TENANT_ID'               => '*',
					'denwabango'              => '*',
					'cardkeijo'               => '*',
					'ansyobango'              => '▲',
					'ryokinplan'              => '▲',
					'contracttype'            => '▲',
					'sousaservice'            => '▲',
					'WWtukehenhaiFLG'         => '▲',
					'WWriyouteisimeyasugaku'  => '▲',
					'WWdaisankokuhassinkisei' => '▲',
					'WCtukehenhaiFLG'         => '▲',
					'WCriyouteisimeyasugaku'  => '▲',
					'WCtuwateisi'             => '▲',
					'cardsaihakoFLG'          => '*',
					'NWmikaituFLG'            => '▲',
					'PINlockkaijoreset'       => '▲'
				),
				'21-1'  => array(
					'TENANT_ID'  => '*',
					'denwabango' => '*'
				),
				'A5-1'  => array(
					'TENANT_ID'         => '*',
					'denwabango'        => '*',
					'MNPzokusei'        => '*',
					'MNPyoyakusyakana'  => '*',
					'MNPyoyakusyakanji' => '*',
					'MNPseinengappi'    => '#'
				),
				'A6-1'  => array(
					'TENANT_ID'  => '*',
					'denwabango' => '*'
				),
				'49-1'  => array(
					'TENANT_ID'  => '*',
					'denwabango' => '*'
				),
				'M01-1' => array(
					'TENANT_ID'         => '*',
					'denwabango'        => '*',
					'MNPyoyakubango'    => '#',
					'MNPzokusei'        => '#',
					'MNPyoyakusyakana'  => '#',
					'MNPyoyakusyakanji' => '#',
					'MNPseinengappi'    => '#',
					'ryokinplan'        => '#',
					'contracttype'      => '#',
					'kensakukoumoku'    => '*'
				)
			);
		} // end of edit
		
		return $acs;
	}
}

if (!function_exists('get_inventory_property')) {
	
	function get_inventory_property()
	{
	}
}

if (!function_exists('get_invoice_property')) {
	
	function get_invoice_property()
	{
	}
}

if (!function_exists('get_analytics_property')) {
	
	function get_analytics_property()
	{
	}
}

/**
 * select properties 定義
 * @param $select_id
 * @return array
 */
if (!function_exists('get_select_property')) {
	function get_select_property($select_id)
	{
		$select_datas = array(
			// トランザクションType
			'transaction_type'        => array(
				SIM_NEW          => '新規申込',
				SIM_HB_NEW       => '新規申込(半黒)',
				SIM_CHANGE       => '既設変更',
				SIM_STOP_RESTART => '利用中断・再開',
				SIM_MNP          => 'MNP予約(継続利用)',
				SIM_MNP_CANCEL   => 'MNP予約解除',
				SIM_END_CONTRACT => '解約',
				SIM_SEARCH       => '検索',
				SIM_OPEN         => '開通'
			),
			// トランザクションType リスト(SIM開通)
			'transaction_type_new'    => array(
				''      => '選択してください',
				'01-1'  => '新規申込(通常開通)',
				'A2-1'  => '新規申込(半黒ROM作成)',
				'M02-1' => '新規申込(半黒ROM⇒開通)',
				'01-2'  => 'MNP転入受付(通常開通)',
				'A2-2'  => 'MNP転入受付(半黒ROM作成)',
				'M02-2' => 'MNP転入受付(半黒ROM⇒開通)',
				'M02-3' => 'カード再発行(白ROM未開通⇒開通)'
			),
			// トランザクションType リスト(SIM変更)
			'transaction_type_modify' => array(
				''      => '選択してください',
				'02-1'  => 'サービス変更',
				'02-2'  => 'カード再発行',
				'21-1'  => '利用中断・再開',
				'A5-1'  => 'MNP予約',
				'A6-1'  => 'MNP予約解除',
				'49-1'  => '解約',
				'M01-1' => '検索',
			),
			// カード形状
			'cardkeijo'               => array(
				'0' => '通常SIMカード',
				'1' => 'miniSIMカード',
				'3' => 'nanoSIMカード',
			),
			// MNP属性
			'MNPzokusei'              => array(
				''  => '選択してください',
				'1' => '男性',
				'2' => '女性',
				'3' => '法人',
				'9' => 'その他'
			),
			// WC利用停止目安額
			'WCriyouteisimeyasugaku'  => array(
				''         => '代表回線継承',
				'5000'     => '5000円',
				'10000'    => '10000円',
				'20000'    => '20000円',
				'30000'    => '30000円',
				'40000'    => '40000円',
				'50000'    => '50000円',
				'60000'    => '60000円',
				'70000'    => '70000円',
				'80000'    => '80000円',
				'90000'    => '90000円',
				'100000'   => '100000円',
				'150000'   => '150000円',
				'200000'   => '200000円',
				'250000'   => '250000円',
				'300000'   => '300000円',
				'400000'   => '400000円',
				'500000'   => '500000円',
				'600000'   => '600000円',
				'800000'   => '800000円',
				'1000000'  => '1000000円',
				'99999999' => '無制限'
			),
			// WW利用停止目安額
			'WWriyouteisimeyasugaku'  => array(
				''         => '代表回線継承',
				'50000'    => '50000円',
				'100000'   => '100000円',
				'200000'   => '200000円',
				'300000'   => '300000円',
				'400000'   => '400000円',
				'500000'   => '500000円',
				'600000'   => '600000円',
				'700000'   => '700000円',
				'800000'   => '800000円',
				'900000'   => '900000円',
				'1000000'  => '1000000円',
				'99999999' => '無制限'
			),
			// ページサイズ
			'page_size'               => array(
				//                       '10' => '10 件表示',
				//                       '20' => '20 件表示',
				'50'  => '50 件表示',
				'100' => '100 件表示',
				'200' => '200 件表示'
			),
			// 入力ステータス
			'input_status'            => array(
				INPUT_STATUS_UNDO  => '未登録',   // '未入力',
				INPUT_STATUS_DOING => '登録中',   //'入力中',
				INPUT_STATUS_DONE  => '登録完了'   //'入力完了'
			),
			// コールステータス
			'call_status'             => array(
//                         CALL_STATUS_UNDO => '未処理',
				CALL_STATUS_UNDO    => '未実行',
				CALL_STATUS_DOING   => '実行中',
				CALL_STATUS_STOP    => '中断',
				CALL_STATUS_DONE    => '実行完了',
				CALL_STATUS_RESERVE => '実行予約'
			),
			// 予約ステータス
//                 'reserve_status' => array (
//                         URESERVED => '未予約',
//                         RESERVED => '予約済'
//                 ),
			// 契約種別
			'contracttype'            => array(
				'1' => '第2種卸FOMA',
				'2' => '第3種卸FOMA総合',
				'3' => '第3種卸FOMAユビキタス',
				'4' => '第2種卸Xi',
				'5' => '第3種卸Xi 卸タイプXi',
				'6' => '第3種卸Xi ユビキタス'
			),
			// 料金プラン
			'ryokinplan'              => array(
				'A1046' => 'タイプSS バリュー',
				'A1047' => 'タイプS バリュー',
				'A1048' => 'タイプM バリュー',
				'A1049' => 'タイプL バリュー',
				'A1050' => 'タイプLL バリュー',
				'A1089' => '卸FOMA特定接続プラン',
				'AC001' => '卸FOMAユビキタスプラン',
				'AJ010' => '卸Xi特定接続プラン',
				'AJ034' => '卸タイプXi',
				'AJ055' => '卸Xiユビキタス'
			),
			// 割引プラン/オプション
			'sousaservice'            => array(
				'C0005' => 'キャッチホン',
				'C0007' => '国際転送サービス',
				'C0013' => '転送でんわサービス',
				'C0020' => '留守番電話サービス',
				'C0324' => 'MVNO課金情報',
				'C0446' => 'SMSプッシュ',
				'0D001' => 'M2M等専用番号払出'
			),
			// 検索項目
			'kensakukoumoku'          => array(
				KENSAKU_KOUMOKU_MNP => 'MNP可否照会',
				KENSAKU_KOUMOKU_WW  => 'WW累積額検索',
				KENSAKU_KOUMOKU_WC  => 'WC累積額検索',
			),
			// SIM項目
			'sim_status'              => array(
				'0' => 'SIM未作成',
				'1' => 'SIM作成済',
			),
			// 発送状態
			'shipment_flag'           => array(
				SHIPMENT_UNDO => '未発送',
				SHIPMENT_DONE => '発送済'
			),
			// SIMタイプ
			'sim_type'                => array(
				SIM_TYPE_SIM   => '標準',
				SIM_TYPE_MICRO => 'micro',
				SIM_TYPE_MULTI => 'マルチ',
				SIM_TYPE_NANO  => 'Nano'
			),
		);
		
		return isset($select_datas[$select_id]) ? $select_datas[$select_id] : array();
	}
}
