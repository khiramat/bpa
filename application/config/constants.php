<?php
defined('BASEPATH') or exit('No direct script access allowed');

/*
 |--------------------------------------------------------------------------
 | Display Debug backtrace
 |--------------------------------------------------------------------------
 |
 | If set to TRUE, a backtrace will be displayed along with php errors. If
 | error_reporting is disabled, the backtrace will not display, regardless
 | of this setting
 |
 */
defined('SHOW_DEBUG_BACKTRACE') or define('SHOW_DEBUG_BACKTRACE', true);

/*
 |--------------------------------------------------------------------------
 | File and Directory Modes
 |--------------------------------------------------------------------------
 |
 | These prefs are used when checking and setting modes when working
 | with the file system.  The defaults are fine on servers with proper
 | security, but you may wish (or even need) to change the values in
 | certain environments (Apache running a separate process for each
 | user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
 | always be used to set the mode correctly.
 |
 */
defined('FILE_READ_MODE') or define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') or define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE') or define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE') or define('DIR_WRITE_MODE', 0755);

/*
 |--------------------------------------------------------------------------
 | File Stream Modes
 |--------------------------------------------------------------------------
 |
 | These modes are used when working with fopen()/popen()
 |
 */
defined('FOPEN_READ') or define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE') or define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE') or define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb');            // truncates existing file data, use with care
defined('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE') or define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE') or define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE') or define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT') or define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT') or define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

/*
 |--------------------------------------------------------------------------
 | Exit Status Codes
 |--------------------------------------------------------------------------
 |
 | Used to indicate the conditions under which the script is exit()ing.
 | While there is no universal standard for error codes, there are some
 | broad conventions.  Three such conventions are mentioned below, for
 | those who wish to make use of them.  The CodeIgniter defaults were
 | chosen for the least overlap with these conventions, while still
 | leaving room for others to be defined in future versions and user
 | applications.
 |
 | The three main conventions used for determining exit status codes
 | are as follows:
 |
 |    Standard C/C++ Library (stdlibc):
 |       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
 |       (This link also contains other GNU-specific conventions)
 |    BSD sysexits.h:
 |       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
 |    Bash scripting:
 |       http://tldp.org/LDP/abs/html/exitcodes.html
 |
 */
defined('EXIT_SUCCESS') or define('EXIT_SUCCESS', 0);               // no errors
defined('EXIT_ERROR') or define('EXIT_ERROR', 1);                   // generic error
defined('EXIT_CONFIG') or define('EXIT_CONFIG', 3);                 // configuration error
defined('EXIT_UNKNOWN_FILE') or define('EXIT_UNKNOWN_FILE', 4);     // file not found
defined('EXIT_UNKNOWN_CLASS') or define('EXIT_UNKNOWN_CLASS', 5);   // unknown class
defined('EXIT_UNKNOWN_METHOD') or define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT') or define('EXIT_USER_INPUT', 7);         // invalid user input
defined('EXIT_DATABASE') or define('EXIT_DATABASE', 8);             // database error
defined('EXIT__AUTO_MIN') or define('EXIT__AUTO_MIN', 9);           // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX') or define('EXIT__AUTO_MAX', 125);         // highest automatically-assigned error code


/*
 |--------------------------------------------------------------------------
 | トランザクションタイプ定義
 |--------------------------------------------------------------------------
 | 01: 新規申込
 | A2: 新規申込(半黒)
 | 02: 既設変更
 | 21: 利用中断・再開
 | A5: MNP予約(継続利用)
 | A6: MNP予約解除
 | 49: 解約
 | M01:検索
 | M02: 開通
 */
defined('SIM_NEW') or define('SIM_NEW', '01');                   // 新規申込
defined('SIM_HB_NEW') or define('SIM_HB_NEW', 'A2');             // 新規申込(半黒)
defined('SIM_CHANGE') or define('SIM_CHANGE', '02');             // 既設変更
defined('SIM_STOP_RESTART') or define('SIM_STOP_RESTART', '21'); // 利用中断・再開
defined('SIM_MNP') or define('SIM_MNP', 'A5');                   // MNP予約(継続利用)
defined('SIM_MNP_CANCEL') or define('SIM_MNP_CANCEL', 'A6');     // MNP予約解除
defined('SIM_END_CONTRACT') or define('SIM_END_CONTRACT', '49'); // 解約
defined('SIM_SEARCH') or define('SIM_SEARCH', 'M01');            // 検索
defined('SIM_OPEN') or define('SIM_OPEN', 'M02');                // 開通


/*
 |--------------------------------------------------------------------------
 | 入力ステータス定義
 |--------------------------------------------------------------------------
 | 0: 未入力
 | 1: 入力中
 | 2: 入力完了
 */
defined('INPUT_STATUS_UNDO') or define('INPUT_STATUS_UNDO', '0');   // 未入力
defined('INPUT_STATUS_DOING') or define('INPUT_STATUS_DOING', '1'); // 入力中
defined('INPUT_STATUS_DONE') or define('INPUT_STATUS_DONE', '2');   // 入力完了
/*
 |--------------------------------------------------------------------------
 | コールステータス定義
 |--------------------------------------------------------------------------
 | 0: 未処理
 | 1: 実行中
 | 2: 中断
 | 3: 実行完了
 */
defined('CALL_STATUS_UNDO') or define('CALL_STATUS_UNDO', '0');       // 未処理
defined('CALL_STATUS_DOING') or define('CALL_STATUS_DOING', '1');     // 実行中
defined('CALL_STATUS_STOP') or define('CALL_STATUS_STOP', '2');       // 中断
defined('CALL_STATUS_DONE') or define('CALL_STATUS_DONE', '3');       // 実行完了
defined('CALL_STATUS_RESERVE') or define('CALL_STATUS_RESERVE', '4'); // 実行予約


/*
 |--------------------------------------------------------------------------
 | 入力区分定義
 |--------------------------------------------------------------------------
 | 0: ファイル
 | 1: 画面
 */
defined('INPUT_KUBUN_FILE') or define('INPUT_KUBUN_FILE', '0'); // ファイル
defined('INPUT_KUBUN_PAGE') or define('INPUT_KUBUN_PAGE', '1'); // 画面


/*
 |--------------------------------------------------------------------------
 | 削除フラグ定義
 |--------------------------------------------------------------------------
 | 0: 未削除
 | 1: 削除済
 */
defined('DELETE_FLG_NOT_DELETE') or define('DELETE_FLG_NOT_DELETE', '0'); // 未削除
defined('DELETE_FLG_DELETED') or define('DELETE_FLG_DELETED', '1');       //  削除済

/*
 |--------------------------------------------------------------------------
 | カード形状定義
 |--------------------------------------------------------------------------
 | 0: 通常SIMカード
 | 1: miniSIMカード
 | 3: nanoSIMカード
 */
defined('CARD_KEIJO_SIM') or define('CARD_KEIJO_SIM', '0');   // 通常SIMカード
defined('CARD_KEIJO_MINI') or define('CARD_KEIJO_MINI', '1'); // miniSIMカード
defined('CARD_KEIJO_NANO') or define('CARD_KEIJO_NANO', '3'); // nanoSIMカード

/*
 |--------------------------------------------------------------------------
 | MNP属性定義
 |--------------------------------------------------------------------------
 | 1: 男性
 | 2: 女性
 | 3: 法人
 | 9: その他
 */
defined('MNP_ZOKUSEI_MALE') or define('MNP_ZOKUSEI_MALE', '1');     // 男性
defined('MNP_ZOKUSEI_FEMALE') or define('MNP_ZOKUSEI_FEMALE', '2'); // 女性
defined('MNP_ZOKUSEI_CORP') or define('MNP_ZOKUSEI_CORP', '3');     // 法人
defined('MNP_ZOKUSEI_OTHER') or define('MNP_ZOKUSEI_OTHER', '9');   // その他


/*
 |--------------------------------------------------------------------------
 | 付変廃フラグ定義
 |--------------------------------------------------------------------------
 | 0: 廃止
 | 1: 新付
 | 2: 変更
 */
defined('TUKEHENHAI_FLG_ABOLITION') or define('TUKEHENHAI_FLG_ABOLITION', '0'); // 廃止
defined('TUKEHENHAI_FLG_NEW') or define('TUKEHENHAI_FLG_NEW', '1');             // 新付
defined('TUKEHENHAI_FLG_CHANGE') or define('TUKEHENHAI_FLG_CHANGE', '2');       // 変更

/*
 |--------------------------------------------------------------------------
 | 第三国発信規制定義
 |--------------------------------------------------------------------------
 | 0: 規制しない
 | 1: 規制する
 */
defined('HASSIN_KISEI_NO_REGULATION') or define('HASSIN_KISEI_NO_REGULATION', '0'); // 規制しない
defined('HASSIN_KISEI_REGULATION') or define('HASSIN_KISEI_REGULATION', '1');       // 規制する


/*
 |--------------------------------------------------------------------------
 | 通話停止定義
 |--------------------------------------------------------------------------
 | 0: 停止しない
 | 1: 停止する
 */
defined('CALL_NOT_STOP') or define('CALL_NOT_STOP', '0'); // 停止しない
defined('CALL_STOP') or define('CALL_STOP', '1');         // 停止する


/*
 |--------------------------------------------------------------------------
 | カード再発行フラグ定義
 |--------------------------------------------------------------------------
 | 1: 再発行
 */
defined('CARD_SAIHAKO') or define('CARD_SAIHAKO', '1'); // 再発行


/*
 |--------------------------------------------------------------------------
 | NW未開通フラグ定義
 |--------------------------------------------------------------------------
 | 1: 白ROM未開通
 */
defined('NW_MIKAITU') or define('NW_MIKAITU', '1'); // 白ROM未開通


/*
 |--------------------------------------------------------------------------
 | PINロック解除コードリセット定義
 |--------------------------------------------------------------------------
 | 1: PINロック解除あり
 */
defined('PIN_LOCK_KAIJO_RESET') or define('PIN_LOCK_KAIJO_RESET', '1'); // PINロック解除あり


/*
 |--------------------------------------------------------------------------
 | 検索項目定義
 |--------------------------------------------------------------------------
 | 1: MNP可否照会
 | 2: WW累積額検索
 | 3: WC累積額検索
 */
defined('KENSAKU_KOUMOKU_MNP') or define('KENSAKU_KOUMOKU_MNP', '1'); // MNP可否照会
defined('KENSAKU_KOUMOKU_WW') or define('KENSAKU_KOUMOKU_WW', '2');   // WW累積額検索
defined('KENSAKU_KOUMOKU_WC') or define('KENSAKU_KOUMOKU_WC', '3');   // WC累積額検索


/*
 |--------------------------------------------------------------------------
 | 結果ステータス定義
 |--------------------------------------------------------------------------
 | 0: OK
 | 1: NG
 */
defined('RESULT_OK') or define('RESULT_OK', '0'); // OK
defined('RESULT_NG') or define('RESULT_NG', '1'); // NG

/*
 |--------------------------------------------------------------------------
 | 予約ステータス定義
 |--------------------------------------------------------------------------
 | 0: 未予約(default)
 | 1: 予約
 */
//defined('RESERVED') or define('RESERVED', '1'); // 予約済み
//defined('UNRESERVED') or define('URESERVED', '0'); // 未予約

/*
 * |--------------------------------------------------------------------------
 * | HTTPステータス一覧
 * |--------------------------------------------------------------------------
 * | 200: HTTP_OK
 * | 400: HTTP_BAD_REQUEST
 * | 410: HTTP_GONE
 * | 422: HTTP_UNPROCESSABLE_ENTITY
 * | 500: HTTP_INTERNAL_SERVER_ERROR
 * | 503: HTTP_SERVICE_UNAVAILABLE
 */
defined('HTTP_OK') or define('HTTP_OK', '200');                                       // リクエストが成功した場合に応答
defined('HTTP_BAD_REQUEST') or define('HTTP_BAD_REQUEST', '400');                     // リクエストのパースができない場合に応答
defined('HTTP_GONE') or define('HTTP_GONE', '410');                                   // APIの公開が終了した場合に応答
defined('HTTP_UNPROCESSABLE_ENTITY') or define('HTTP_UNPROCESSABLE_ENTITY', '422');   // リクエストのバリデーションエラーが発生した場合に応答
defined('HTTP_INTERNAL_SERVER_ERROR') or define('HTTP_INTERNAL_SERVER_ERROR', '500'); // エラーが発生した場合に応答
defined('HTTP_SERVICE_UNAVAILABLE') or define('HTTP_SERVICE_UNAVAILABLE', '503');     // 一時的に処理を停止している場合に応答
defined('HTTP_NOT_FOUND') or define('HTTP_NOT_FOUND', '404');                         // URLエラー

/*
 * |--------------------------------------------------------------------------
 * | API 
 * |--------------------------------------------------------------------------
 * | 1: API_STATUS_REQUEST_OK
 * | 2: API_STATUS_RESPONSE_OK
 * | 3: API_STATUS_REQUEST_ERROR
 * | 9: API_STATUS_ERROR
 */
defined('API_STATUS_REQUEST_OK') or define('API_STATUS_REQUEST_OK', 1);       //ALADIN要求OK
defined('API_STATUS_RESPONSE_OK') or define('API_STATUS_RESPONSE_OK', 2);     //完了
defined('API_STATUS_REQUEST_ERROR') or define('API_STATUS_REQUEST_ERROR', 3); //ALADIN要求エラー
defined('API_STATUS_ERROR') or define('API_STATUS_ERROR', 9);                 //更新エラー

/*
 |--------------------------------------------------------------------------
 | SIM発送ステータス定義
 |--------------------------------------------------------------------------
 | 0: 未発送
 | 1: 発送済
 */
defined('SHIPMENT_UNDO') or define('SHIPMENT_UNDO', '0'); // 未発送
defined('SHIPMENT_DONE') or define('SHIPMENT_DONE', '1'); // 発送済

/*
 |--------------------------------------------------------------------------
 | SIMタイプ定義
 |--------------------------------------------------------------------------
 | 0: 標準
 | 1: micro
 | 2: マルチ
 | 3: Nano
 */
defined('SIM_TYPE_SIM') or define('SIM_TYPE_SIM', '0');     // 標準
defined('SIM_TYPE_MICRO') or define('SIM_TYPE_MICRO', '1'); // micro
defined('SIM_TYPE_MULTI') or define('SIM_TYPE_MULTI', '2'); // マルチ
defined('SIM_TYPE_NANO') or define('SIM_TYPE_NANO', '3');   // Nano

/*
 |--------------------------------------------------------------------------
 | SIM状態ステータス定義
 |--------------------------------------------------------------------------
 | 0: 未作成
 | 1: 作成済
 */
defined('SIM_STATUS_UNDO') or define('SIM_STATUS_UNDO', '0'); // 未作成
defined('SIM_STATUS_DONE') or define('SIM_STATUS_DONE', '1'); // 作成済

